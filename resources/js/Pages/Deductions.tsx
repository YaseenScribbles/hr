import { useEffect, useState } from "react";
import Layout from "../Layouts/Layout";
import { PageProps } from "@inertiajs/core";
import toast, { Toaster } from "react-hot-toast";
import { format } from "date-fns";
import { router, useForm } from "@inertiajs/react";

interface Props extends PageProps {
    deductions: Deduction[];
    employees: {
        id: number;
        name: string;
    }[];
    companies: {
        id: number;
        name: string;
    }[];
}

type Company = {
    id: number;
    name: string;
};

type Deduction = {
    id?: number;
    employee_id: number;
    from_date: string;
    to_date: string;
    type: string;
    percentage: number | null;
    amount: number;
    created_at?: string;
    employee: {
        id: number;
        name: string;
    }
}

const Deduction = ({ deductions, flash, auth, employees, companies }: Props) => {
    const [showModal, setShowModal] = useState(false);
    const [showGenerateModal, setShowGenerateModal] = useState(false);
    const [editItem, setEditItem] = useState<
        | {
            id: number;
            employee_id: number;
            from_date: string;
            to_date: string;
            type: string;
            percentage: number | null;
            amount: number;
        }
        | undefined
    >(undefined);
    const [editMode, setEditMode] = useState(false);

    useEffect(() => {
        if (flash?.toast) {
            toast[flash.toast.type](flash.toast.message);
        }
    }, [flash]);

    return (
        <Layout role={auth.user?.role}>
            <div className="p-4">
                <div className="flex justify-between items-center mb-4">
                    <h1 className="text-2xl text-white font-bold">
                        Deductions
                    </h1>
                    <div className="flex gap-2">
                        <button
                            className="bg-emerald-500 text-white px-4 py-2 rounded-md hover:bg-emerald-600 cursor-pointer"
                            onClick={() => setShowGenerateModal(true)}
                        >
                            Generate Salary
                        </button>
                        <button
                            className="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 cursor-pointer"
                            onClick={() => setShowModal(true)}
                        >
                            Add Deduction
                        </button>
                    </div>
                </div>
                <div className="bg-gray-700/30 rounded-md shadow-md shadow-gray-500 p-4">
                    <table className="w-full text-left text-white">
                        <thead>
                            <tr className="border-b border-gray-500">
                                <th className="py-2">Employee</th>
                                <th className="py-2">From Date</th>
                                <th className="py-2">To Date</th>
                                <th className="py-2">Type</th>
                                <th className="py-2">Percentage</th>
                                <th className="py-2">Amount</th>
                                <th className="py-2">Created At</th>
                                <th className="py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {deductions &&
                                deductions.map((ded) => (
                                    <tr key={ded.id}>
                                        <td className="py-2">{ded.employee.name}</td>
                                        <td className="py-2">{format(ded.from_date, "dd-MM-yyyy")}</td>
                                        <td className="py-2">{format(ded.to_date, "dd-MM-yyyy")}</td>
                                        <td className="py-2">{ded.type.toUpperCase()}</td>
                                        <td className="py-2">{ded.percentage ?? "-"}</td>
                                        <td className="py-2">{ded.amount}</td>
                                        <td className="py-2">
                                            {format(
                                                new Date(ded.created_at!),
                                                "MMM dd, yyyy hh:mm a",
                                            )}
                                        </td>
                                        <td className="py-2">
                                            <button
                                                className="bg-green-500 text-white px-2 py-1 rounded-md hover:bg-green-600 mr-2 cursor-pointer"
                                                onClick={() => {
                                                    setEditItem({
                                                        id: ded.id!,
                                                        employee_id: ded.employee_id,
                                                        from_date: ded.from_date,
                                                        to_date: ded.to_date,
                                                        type: ded.type,
                                                        percentage: ded.percentage ?? null,
                                                        amount: ded.amount,
                                                    });
                                                    setEditMode(true);
                                                    setShowModal(true);
                                                }}
                                            >
                                                Edit
                                            </button>
                                            {
                                                auth.user?.role == "admin" && (
                                                    <button
                                                        className="bg-red-500 text-white px-2 py-1 rounded-md hover:bg-red-600 cursor-pointer"
                                                        onClick={() => {
                                                            router.delete(
                                                                `/deductions/${ded.id}`,
                                                            );
                                                        }}
                                                    >
                                                        Delete
                                                    </button>
                                                )}
                                        </td>
                                    </tr>
                                ))}
                        </tbody>
                    </table>
                </div>
            </div>
            <GenerateSalaryModal
                isOpen={showGenerateModal}
                onClose={() => setShowGenerateModal(false)}
                companies={companies}
            />
            <Modal
                isOpen={showModal}
                onClose={() => {
                    setShowModal(false);
                    setEditMode(false);
                    setEditItem(undefined);
                }}
                editMode={editMode}
                editItem={editItem}
                employees={employees}
            />
        </Layout>
    );
};
export default Deduction;

interface ModalProps {
    isOpen: boolean;
    onClose: () => void;
    editMode: boolean;
    editItem?: {
        id: number;
        employee_id: number;
        from_date: string;
        to_date: string;
        type: string;
        percentage: number | null;
        amount: number;
    };
    employees: {
        id: number;
        name: string;
    }[];
}

const Modal = ({
    isOpen,
    editMode,
    onClose,
    editItem,
    employees
}: ModalProps) => {
    if (!isOpen) return null;

    const { data, setData, post, errors, processing } = useForm({
        id: editItem?.id ?? undefined,
        employee_id: editItem?.employee_id ?? 0,
        from_date: editItem?.from_date ?? "",
        to_date: editItem?.to_date ?? "",
        type: editItem?.type ?? "",
        percentage: editItem?.percentage ?? null,
        amount: editItem?.amount ?? 0,
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        if (editMode && editItem) {
            // Update logic here (e.g., put request)
            // post(`/departments/${editItem.id}`, { ...data, _method: 'put' });
            post(`deductions/${editItem.id}?_method=put`, {
                onSuccess: () => {
                    onClose();
                },
            });
        } else {
            // Create logic here (e.g., post request)
            // post('/departments', data);
            post("deductions", {
                onSuccess: () => {
                    onClose();
                },
            });
        }
    };

    useEffect(() => {
        if (editMode && editItem) {
            setData({
                id: editItem.id,
                employee_id: editItem.employee_id,
                from_date: editItem.from_date,
                to_date: editItem.to_date,
                type: editItem.type,
                percentage: editItem.percentage,
                amount: editItem.amount,
            });
        } else {
            setData({
                id: undefined,
                employee_id: 0,
                from_date: "",
                to_date: "",
                type: "",
                percentage: null,
                amount: 0,
            });
        }
    }, [editMode, editItem]);

    useEffect(() => {
        if (errors && Object.keys(errors).length > 0) {
            Object.values(errors).forEach((msg) => {
                const message = Array.isArray(msg) ? msg[0] : msg;

                toast.error(message as string, { toasterId: "deductions" });
            });
        }
    }, [errors]);

    return (
        <div className="fixed inset-0 bg-gray-900/50 flex items-center justify-center">
            <div className="bg-gray-800 rounded-md shadow-md shadow-gray-500 p-6 w-2/4 max-h-3/4">
                <div className="flex justify-between items-center mb-4 sticky top-0">
                    <h2 className="text-xl font-bold text-white">
                        {editMode ? "Edit Deduction" : "Add Deduction"}
                    </h2>
                    <button
                        className="text-white text-4xl hover:text-gray-300 cursor-pointer"
                        onClick={onClose}
                    >
                        &times;
                    </button>
                </div>
                <form onSubmit={handleSubmit} className="max-h-100 2xl:max-h-125 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 overflow-auto p-1">

                    <div className="mb-4">
                        <label className="block text-gray-300 mb-1">Employee</label>
                        <select
                            className="w-full px-3 py-2 rounded-md bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value={data.employee_id}
                            onChange={(e) => setData("employee_id", parseInt(e.target.value))}
                            required
                        >
                            <option value="">Select Employee</option>
                            {employees.map((emp) => (
                                <option key={emp.id} value={emp.id}>
                                    {emp.name}
                                </option>
                            ))}
                        </select>
                    </div>

                    {/* Similar input fields for from_date, to_date, type, percentage, amount */}

                    <div className="mb-4">
                        <label className="block text-gray-300 mb-1">From Date</label>
                        <input
                            type="date"
                            className="w-full px-3 py-2 rounded-md bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value={data.from_date}
                            onChange={(e) => setData("from_date", e.target.value)}
                            required
                        />
                    </div>

                    <div className="mb-4">
                        <label className="block text-gray-300 mb-1">To Date</label>
                        <input
                            type="date"
                            className="w-full px-3 py-2 rounded-md bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value={data.to_date}
                            onChange={(e) => setData("to_date", e.target.value)}
                            required
                        />
                    </div>

                    <div className="mb-4">
                        <label className="block text-gray-300 mb-1">Type</label>
                        <select
                            className="w-full px-3 py-2 rounded-md bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value={data.type ?? ""}
                            onChange={(e) => setData("type", e.target.value)}
                            required
                        >
                            <option value="">Select Type</option>
                            <option value="advance">Advance</option>
                            <option value="esi">ESI</option>
                            <option value="pf">PF</option>
                        </select>
                    </div>

                    <div className="mb-4">
                        <label className="block text-gray-300 mb-1">Percentage</label>
                        <input
                            type="number"
                            step="any"
                            className="w-full px-3 py-2 rounded-md bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value={data.percentage ?? ""}
                            onChange={(e) => setData("percentage", parseFloat(e.target.value))}
                        />
                    </div>

                    <div className="mb-4">
                        <label className="block text-gray-300 mb-1">Amount</label>
                        <input
                            type="number"
                            step="any"
                            className="w-full px-3 py-2 rounded-md bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value={data.amount ?? ""}
                            onChange={(e) => setData("amount", parseFloat(e.target.value))}
                        />
                    </div>

                    <button
                        type="submit"
                        className="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 cursor-pointer disabled:bg-gray-500 md:col-span-3"
                        disabled={processing}
                    >
                        {editMode ? "Update" : "Save"}
                    </button>
                </form>
            </div>
            <Toaster position="bottom-right" toasterId="deductions" />
        </div>
    );
};

interface GenerateSalaryModalProps {
    isOpen: boolean;
    onClose: () => void;
    companies: Company[];
}

const GenerateSalaryModal = ({ isOpen, onClose, companies }: GenerateSalaryModalProps) => {
    if (!isOpen) return null;

    const currentYear = new Date().getFullYear();
    const { data, setData, post, errors, processing } = useForm({
        company_id: companies[0]?.id ?? 0,
        month: new Date().getMonth() + 1,
        year: currentYear,
    });

    useEffect(() => {
        if (companies.length && !data.company_id) {
            setData('company_id', companies[0].id);
        }
    }, [companies, data.company_id, setData]);

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/deductions/generate-salary', {
            onSuccess: () => {
                onClose();
            },
        });
    };

    useEffect(() => {
        if (errors && Object.keys(errors).length > 0) {
            Object.values(errors).forEach((msg) => {
                const message = Array.isArray(msg) ? msg[0] : msg;
                toast.error(message as string, { toasterId: 'deductions' });
            });
        }
    }, [errors]);

    const years = [currentYear - 1, currentYear, currentYear + 1];
    const monthNames = [
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December',
    ];

    return (
        <div className="fixed inset-0 bg-gray-900/50 flex items-center justify-center">
            <div className="bg-gray-800 rounded-md shadow-md shadow-gray-500 p-6 w-1/3 max-h-3/4">
                <div className="flex justify-between items-center mb-4 sticky top-0">
                    <h2 className="text-xl font-bold text-white">Generate Salary</h2>
                    <button
                        className="text-white text-4xl hover:text-gray-300 cursor-pointer"
                        onClick={onClose}
                    >
                        &times;
                    </button>
                </div>

                <form onSubmit={handleSubmit} className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div className="mb-4 col-span-2">
                        <label className="block text-gray-300 mb-1">Company</label>
                        <select
                            className="w-full px-3 py-2 rounded-md bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value={data.company_id}
                            onChange={(e) => setData('company_id', parseInt(e.target.value, 10))}
                            required
                        >
                            <option value="">Select Company</option>
                            {companies.map((company) => (
                                <option key={company.id} value={company.id}>
                                    {company.name}
                                </option>
                            ))}
                        </select>
                    </div>

                    <div className="mb-4">
                        <label className="block text-gray-300 mb-1">Month</label>
                        <select
                            className="w-full px-3 py-2 rounded-md bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value={data.month}
                            onChange={(e) => setData('month', parseInt(e.target.value, 10))}
                            required
                        >
                            {monthNames.map((month, index) => (
                                <option key={month} value={index + 1}>
                                    {month}
                                </option>
                            ))}
                        </select>
                    </div>

                    <div className="mb-4">
                        <label className="block text-gray-300 mb-1">Year</label>
                        <select
                            className="w-full px-3 py-2 rounded-md bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value={data.year}
                            onChange={(e) => setData('year', parseInt(e.target.value, 10))}
                            required
                        >
                            {years.map((year) => (
                                <option key={year} value={year}>
                                    {year}
                                </option>
                            ))}
                        </select>
                    </div>

                    <div className="mb-4 sm:col-span-2">
                        <button
                            type="submit"
                            className="w-full bg-emerald-500 text-white py-2 px-4 rounded-md hover:bg-emerald-600 cursor-pointer disabled:bg-gray-500"
                            disabled={processing}
                        >
                            Generate
                        </button>
                    </div>
                </form>
            </div>
            <Toaster position="bottom-right" toasterId="deductions" />
        </div>
    );
};
