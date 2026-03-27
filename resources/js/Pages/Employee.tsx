import { PageProps } from "@inertiajs/core"
import Layout from "../Layouts/Layout"
import { useEffect, useState } from "react"
import toast, { Toaster } from "react-hot-toast"
import { EmployeeDetail, EmployeeFormData, EmployeeListItem, PaginatedData } from "../../../types/global"
import { format } from "date-fns"
import { router, useForm } from "@inertiajs/react"
import EmployeeSection from "./Employee/Partials/EmpInfo"
import PersonalSection from "./Employee/Partials/EmpPersonalInfo"
import FamilyTable from "./Employee/Partials/EmpFamily"
import NomineeTable from "./Employee/Partials/EmpNominee"
import { route } from "ziggy-js"
import Pagination from "../Components/Pagination"
import axios from "axios"
import { mapEmployeeToForm } from "../Helpers/Functions"

interface Props extends PageProps {
    employees: PaginatedData<EmployeeListItem>,
    departments: Option[];
    categories: Option[];
    designations: Option[];
    filter: {
        search: string;
        company_id: string;
        department_id: string;
        designation_id: string;
        status: string;
    }
}

type Option = {
    id: number;
    name: string;
    company_id?: number;
}

const Employee = ({ auth, flash, employees, user_companies, departments, categories, designations, filter }: Props) => {

    const [showModal, setShowModal] = useState(false)
    const [editMode, setEditMode] = useState(false)
    const [editId, setEditId] = useState<number | undefined>(undefined)
    const [filters, setFilters] = useState({
        search: filter?.search || "",
        company_id: filter?.company_id || "",
        department_id: filter?.department_id || "",
        status: filter?.status || "",
    });

    useEffect(() => {
        if (flash && flash.toast) {
            toast[flash.toast.type](flash.toast.message)
        }
    }, [flash])

    useEffect(() => {
        if (filters.search || filters.company_id || filters.department_id || filters.status) {
            const timeout = setTimeout(() => {
                router.get(route("employees.index"), filters, {
                    preserveState: true,
                    replace: true,
                });
            }, 500); // debounce

            return () => clearTimeout(timeout);
        }
    }, [filters]);

    return (
        <>
            <Layout role={auth.user?.role}>
                <div className="p-4">
                    <div className="flex justify-between items-center mb-4">
                        <h1 className="text-2xl text-white font-bold">
                            Employees
                        </h1>
                        <button
                            className="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 cursor-pointer"
                            onClick={() => setShowModal(true)}
                        >
                            Add Employee
                        </button>
                    </div>
                    <div className="flex flex-wrap gap-3 mb-4">
                        {/* 🔍 Search */}
                        <input
                            type="text"
                            placeholder="Search name or mobile..."
                            value={filters.search}
                            onChange={(e) =>
                                setFilters({ ...filters, search: e.target.value })
                            }
                            className="px-3 py-2 rounded-md bg-gray-800 text-white border border-gray-600"
                        />

                        {/* Company */}
                        <select
                            value={filters.company_id}
                            onChange={(e) =>
                                setFilters({ ...filters, company_id: e.target.value })
                            }
                            className="px-3 py-2 rounded-md bg-gray-800 text-white border border-gray-600"
                        >
                            <option value="">All Companies</option>
                            {user_companies.map((c) => (
                                <option key={c.id} value={c.id}>
                                    {c.name}
                                </option>
                            ))}
                        </select>

                        {/* Department */}
                        <select
                            value={filters.department_id}
                            onChange={(e) =>
                                setFilters({ ...filters, department_id: e.target.value })
                            }
                            className="px-3 py-2 rounded-md bg-gray-800 text-white border border-gray-600"
                        >
                            <option value="">All Departments</option>
                            {departments.map((d) => (
                                <option key={d.id} value={d.id}>
                                    {d.name}
                                </option>
                            ))}
                        </select>

                        {/* Status */}
                        <select
                            value={filters.status}
                            onChange={(e) =>
                                setFilters({ ...filters, status: e.target.value })
                            }
                            className="px-3 py-2 rounded-md bg-gray-800 text-white border border-gray-600"
                        >
                            <option value="">All</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>

                        {/* Reset */}
                        <button
                            onClick={() => {
                                const reset = {
                                    search: "",
                                    company_id: "",
                                    department_id: "",
                                    status: "",
                                };
                                setFilters(reset);

                                router.get(route('employees.index'), reset)
                            }}
                            className="px-3 py-2 bg-gray-200 rounded-md cursor-pointer hover:bg-gray-300"
                        >
                            Reset
                        </button>
                    </div>
                    <div className="bg-gray-700/30 rounded-md shadow-md shadow-gray-500 p-4">
                        <table className="w-full text-left text-white">
                            <thead>
                                <tr className="border-b border-gray-500">
                                    <th className="py-2"></th>
                                    <th className="py-2">Name</th>
                                    <th className="py-2">Company</th>
                                    <th className="py-2">Department</th>
                                    <th className="py-2">Designation</th>
                                    <th className="py-2">Mobile</th>
                                    <th className="py-2">Active</th>
                                    <th className="py-2">Created At</th>
                                    <th className="py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                {employees &&
                                    employees.data.map((emp) => (
                                        <tr key={emp.id}>
                                            <td className="p-2">
                                                {emp.personal_detail?.img_path ? (
                                                    <img
                                                        src={`/storage/${emp.personal_detail.img_path}`}
                                                        alt={emp.name}
                                                        className="w-13 h-13 rounded-full object-cover border border-gray-500 shadow"
                                                        onError={(e) => {
                                                            e.currentTarget.style.display = "none";
                                                        }}
                                                    />
                                                ) : (
                                                    <div className="w-13 h-13 rounded-full bg-gray-600 flex items-center justify-center text-sm text-white shadow">
                                                        {emp.name?.charAt(0)?.toUpperCase() || "?"}
                                                    </div>
                                                )}
                                            </td>
                                            <td className="py-2">{emp.name}</td>
                                            <td className="py-2">{emp.company.name}</td>
                                            <td className="py-2">{emp.department.name}</td>
                                            <td className="py-2">{emp.designation.name}</td>
                                            <td className="py-2">{emp.personal_detail?.mobile}</td>
                                            <td className="py-2">
                                                {emp.status ? "Yes" : "No"}
                                            </td>
                                            <td className="py-2">
                                                {format(
                                                    new Date(emp.created_at),
                                                    "MMM dd, yyyy hh:mm a",
                                                )}
                                            </td>
                                            <td className="py-2">
                                                <button
                                                    className="bg-green-500 text-white px-2 py-1 rounded-md hover:bg-green-600 mr-2 cursor-pointer"
                                                    onClick={() => {
                                                        setEditId(emp.id);
                                                        setEditMode(true);
                                                        setShowModal(true);
                                                    }}
                                                >
                                                    Edit
                                                </button>
                                                <button
                                                    className="bg-red-500 text-white px-2 py-1 rounded-md hover:bg-red-600 cursor-pointer"
                                                    onClick={() => {
                                                        router.delete(
                                                            `/employees/${emp.id}`,
                                                        );
                                                    }}
                                                >
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                    ))}
                            </tbody>
                        </table>
                    </div>
                    <Pagination links={employees.links} />
                </div>
            </Layout>
            <Modal
                isOpen={showModal}
                onClose={() => { setShowModal(false); setEditId(undefined); setEditMode(false) }}
                editMode={editMode}
                editId={editId}
                companies={user_companies}
                departments={departments}
                categories={categories}
                designations={designations}
            />
        </>
    )
}

export default Employee

interface ModalProps {
    isOpen: boolean;
    onClose: () => void;
    editMode: boolean;
    editId?: number;
    companies: Option[];
    departments: Option[];
    categories: Option[];
    designations: Option[];
}


const steps = [
    "Employee",
    "Personal",
    "Family",
    "Nominees",
];

const Modal = ({ isOpen, onClose, editMode, companies, departments, categories, designations, editId }: ModalProps) => {
    if (!isOpen) return null;
    const [step, setStep] = useState(0);
    const { data, setData, processing, post, errors } = useForm<EmployeeFormData>({
        employee: {
            code: "",
            name: "",
            gender: "male",
            d_o_j: "",
            status: true,
            audit: true,
            company_id: 0,
            dept_id: 0,
            cat_id: 0,
            des_id: 0,
        },
        personal: {
            physically_challenged: false,
        },
        nominees: [],
        family: [],
    })
    const [fetching, setFetching] = useState(false)

    const next = () => setStep((s) => Math.min(s + 1, steps.length - 1));
    const prev = () => setStep((s) => Math.max(s - 1, 0));

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        if (editMode && editId) {
            post(`employees/${editId}?_method=PUT`, {
                preserveScroll: true,
                onSuccess: () => onClose(),
            });
        } else {
            post(route('employees.store'), {
                preserveScroll: true,
                onSuccess: () => onClose(),
            });
        }
    };

    const handleEdit = async (id: number) => {
        try {
            setFetching(true)
            const res = await axios.get(`/employees/${id}`);
            setData(mapEmployeeToForm(res.data as EmployeeDetail))
        } catch (err) {
            toast.error(err as string, {
                toasterId: "employeeModal"
            });
        } finally {
            setFetching(false)
        }
    };

    useEffect(() => {
        if (errors && Object.keys(errors).length > 0) {
            Object.values(errors).forEach((msg) => {
                const message = Array.isArray(msg) ? msg[0] : msg;

                toast.error(message as string, {
                    toasterId: "employeeModal"
                });
            });
        }
    }, [errors]);

    useEffect(() => {
        if (editId) {
            handleEdit(editId)
        }
    }, [editId])

    return (
        <div className="fixed inset-0 bg-black/70 z-50 flex items-center justify-center">
            <div className="bg-gray-900 w-full max-w-5xl rounded-xl shadow-lg overflow-hidden">

                {/* Header */}
                <div className="flex items-center justify-between p-4 bg-gray-800/50 shadow">
                    <h2 className="text-xl font-bold text-white">
                        {editMode ? "Edit Employee" : "Add Employee"}
                    </h2>
                    <button
                        className="text-white text-3xl hover:text-gray-300 cursor-pointer"
                        onClick={onClose}
                    >
                        &times;
                    </button>
                </div>

                {/* Stepper */}
                <div className="flex items-center justify-between px-6 py-4 border-b border-gray-700">
                    {steps.map((label, index) => (
                        <div key={index} className="flex-1 flex items-center">

                            {/* Circle */}
                            <div
                                className={`w-8 h-8 flex items-center justify-center rounded-full text-sm font-bold
                                ${index <= step
                                        ? "bg-blue-500 text-white"
                                        : "bg-gray-700 text-gray-300"
                                    }`}
                            >
                                {index + 1}
                            </div>

                            {/* Label */}
                            <span className="ml-2 text-sm text-white hidden sm:block">
                                {label}
                            </span>

                            {/* Line */}
                            {index < steps.length - 1 && (
                                <div className="flex-1 h-0.5 bg-gray-700 mx-2">
                                    <div
                                        className={`h-full ${index < step ? "bg-blue-500" : ""}`}
                                    />
                                </div>
                            )}
                        </div>
                    ))}
                </div>

                {/* Content */}
                <div className="p-6 min-h-75 max-h-125 overflow-auto">

                    {step === 0 && (
                        <div>
                            <h3 className="text-white mb-4">Employee Info</h3>
                            {/* EmployeeForm */}
                            <EmployeeSection
                                data={data}
                                setData={setData}
                                companies={companies}
                                departments={departments}
                                categories={categories}
                                designations={designations}
                            />
                        </div>
                    )}

                    {step === 1 && (
                        <div>
                            <h3 className="text-white mb-4">Personal Details</h3>
                            {/* PersonalForm */}
                            <PersonalSection data={data} setData={setData} />
                        </div>
                    )}

                    {step === 2 && (
                        <div>
                            <h3 className="text-white mb-4">Family</h3>
                            {/* Family Table */}
                            <FamilyTable data={data} setData={setData} />
                        </div>
                    )}

                    {step === 3 && (
                        <div>
                            <h3 className="text-white mb-4">Nominees</h3>
                            {/* NomineeTable */}
                            <NomineeTable data={data} setData={setData} />
                        </div>
                    )}

                </div>

                {/* Footer */}
                <div className="flex justify-between items-center p-4 border-t border-gray-700">

                    <button
                        onClick={prev}
                        disabled={step === 0}
                        className="px-4 py-2 bg-gray-700 text-white rounded disabled:opacity-50 cursor-pointer"
                    >
                        Previous
                    </button>

                    {step < steps.length - 1 ? (
                        <button
                            onClick={next}
                            className="px-4 py-2 bg-blue-600 text-white rounded cursor-pointer disabled:bg-blue-500/50"
                            disabled={fetching}
                        >
                            Next
                        </button>
                    ) : (
                        <button
                            className="px-4 py-2 bg-green-600 text-white rounded cursor-pointer"
                            onClick={handleSubmit}
                            disabled={processing || fetching}
                        >
                            Submit
                        </button>
                    )}
                </div>
            </div>
            <Toaster position="bottom-right" toasterId="employeeModal" />
        </div>
    );
};
