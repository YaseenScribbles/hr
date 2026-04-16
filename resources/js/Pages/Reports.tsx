import { useEffect, useState } from "react";
import Layout from "../Layouts/Layout";
import { PageProps } from "@inertiajs/core";
import toast from "react-hot-toast";
import { router } from "@inertiajs/react";
import { route } from "ziggy-js";
import Pagination from "../Components/Pagination";
import { PaginatedData } from "../../../types/global";

interface Props extends PageProps {
    filter: {
        search: string;
        company_id: string;
        department_id: string;
        designation_id: string;
        category_id: string;
        status: string;
    };
    departments: Option[];
    designations: Option[];
    employees: PaginatedData<{
        id: number;
        code: string;
        name: string;

        company: {
            id: number;
            name: string;
        };

        department: {
            id: number;
            name: string;
        };

        designation: {
            id: number;
            name: string;
        };
    }>;
}

type Option = {
    id: number;
    name: string;
    company_id?: number;
};

type ModalType = "attendance" | "payslip" | "timings";

type ModalPayload = {
    employeeId: number | null;
    companyId: number | string;
    month: number;
    year: number;
};

const monthOptions = [
    { value: 1, label: "January" },
    { value: 2, label: "February" },
    { value: 3, label: "March" },
    { value: 4, label: "April" },
    { value: 5, label: "May" },
    { value: 6, label: "June" },
    { value: 7, label: "July" },
    { value: 8, label: "August" },
    { value: 9, label: "September" },
    { value: 10, label: "October" },
    { value: 11, label: "November" },
    { value: 12, label: "December" },
];

const currentYear = new Date().getFullYear();
const yearOptions = [currentYear - 1, currentYear, currentYear + 1];

const Reports = ({ auth, flash, filter, user_companies, departments, designations, employees }: Props) => {
    const [modalType, setModalType] = useState<ModalType | null>(null);
    const [modalPayload, setModalPayload] = useState<ModalPayload>({
        employeeId: null,
        companyId: user_companies?.[0]?.id ?? "",
        month: new Date().getMonth() + 1,
        year: currentYear,
    });
    const [filters, setFilters] = useState({
        search: filter?.search || "",
        company_id: filter?.company_id || "",
        department_id: filter?.department_id || "",
        designation_id: filter?.designation_id || "",
        category_id: filter?.category_id || "",
        status: filter?.status || "",
    });

    useEffect(() => {
        if (flash && flash.toast) {
            toast[flash.toast.type](flash.toast.message);
        }
    }, [flash]);

    const openModal = (type: ModalType, employeeId: number | null = null) => {
        setModalPayload((prev) => ({
            ...prev,
            employeeId,
            companyId: prev.companyId || user_companies?.[0]?.id || "",
            month: new Date().getMonth() + 1,
            year: currentYear,
        }));
        setModalType(type);
    };

    const closeModal = () => {
        setModalType(null);
    };

    const handleGenerate = () => {
        if (!modalType) {
            return;
        }

        const { employeeId, companyId, month, year } = modalPayload;
        let url = "";

        if (modalType === "payslip" && employeeId) {
            url = `${route("pdf.form-25b", { employee: employeeId })}?month=${month}&year=${year}&print=1`;
        }

        if (modalType === "timings" && employeeId) {
            url = `${route("pdf.timing-report", { employee: employeeId })}?month=${month}&year=${year}&print=1`;
        }

        if (modalType === "attendance" && companyId) {
            url = `${route("pdf.attendance-report", { company: companyId })}?month=${month}&year=${year}&print=1`;
        }

        if (!url) return;

        const newWindow = window.open("about:blank", "_blank");
        if (!newWindow) {
            toast.error("Please allow popups to generate the report.");
            return;
        }

        const checkUrl = url.replace("print=1", "check=1");

        fetch(checkUrl, {
            credentials: "same-origin",
            headers: { Accept: "application/json" },
        })
            .then(async (response) => {
                if (response.ok) {
                    newWindow.location.href = url;
                    closeModal();
                    return;
                }
                newWindow.close();
                const data = await response.json().catch(() => null);
                toast.error(data?.message || "Cannot generate report. Please check the selected month.");
            })
            .catch(() => {
                newWindow.close();
                toast.error("Unable to validate report data. Please try again.");
            });
    };

    const renderModalTitle = () => {
        if (modalType === "payslip") return "Payslip Report";
        if (modalType === "timings") return "Timing Report";
        if (modalType === "attendance") return "Attendance Report";
        return "";
    };

    useEffect(() => {
        if (filters.search || filters.company_id || filters.department_id || filters.designation_id || filters.status) {
            const timeout = setTimeout(() => {
                router.get(route("reports.index"), filters, {
                    preserveState: true,
                    replace: true,
                });
            }, 500); // debounce

            return () => clearTimeout(timeout);
        }
    }, [filters]);

    return (
        <Layout role={auth.user?.role} userName={auth.user?.name}>
            <div className="p-4">
                <div className="flex justify-between items-center mb-4">
                    <h1 className="text-2xl text-white font-bold">Reports</h1>
                    <button
                        className="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 cursor-pointer"
                        onClick={() => openModal("attendance")}
                    >
                        Attendance Report
                    </button>
                </div>
                <div className="flex flex-wrap gap-3 mb-4">
                    <input
                        type="text"
                        placeholder="Search name"
                        value={filters.search}
                        onChange={(e) => setFilters({ ...filters, search: e.target.value })}
                        className="px-3 py-2 rounded-md bg-gray-800 text-white border border-gray-600"
                    />
                    <select
                        value={filters.company_id}
                        onChange={(e) => setFilters({ ...filters, company_id: e.target.value })}
                        className="px-3 py-2 rounded-md bg-gray-800 text-white border border-gray-600"
                    >
                        <option value="">All Companies</option>
                        {user_companies.map((c) => (
                            <option key={c.id} value={c.id}>
                                {c.name}
                            </option>
                        ))}
                    </select>
                    <select
                        value={filters.department_id}
                        onChange={(e) => setFilters({ ...filters, department_id: e.target.value })}
                        className="px-3 py-2 rounded-md bg-gray-800 text-white border border-gray-600"
                    >
                        <option value="">All Departments</option>
                        {departments.map((d) => (
                            <option key={d.id} value={d.id}>
                                {d.name}
                            </option>
                        ))}
                    </select>
                    <select
                        value={filters.designation_id}
                        onChange={(e) => setFilters({ ...filters, designation_id: e.target.value })}
                        className="px-3 py-2 rounded-md bg-gray-800 text-white border border-gray-600"
                    >
                        <option value="">All Designations</option>
                        {designations.map((d) => (
                            <option key={d.id} value={d.id}>
                                {d.name}
                            </option>
                        ))}
                    </select>
                    <select
                        value={filters.status}
                        onChange={(e) => setFilters({ ...filters, status: e.target.value })}
                        className="px-3 py-2 rounded-md bg-gray-800 text-white border border-gray-600"
                    >
                        <option value="">All</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    <button
                        onClick={() => {
                            const reset = {
                                search: "",
                                company_id: "",
                                department_id: "",
                                designation_id: "",
                                category_id: "",
                                status: "",
                            };
                            setFilters(reset);
                            router.get(route("reports.index"), reset);
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
                                <th className="p-2">#</th>
                                <th className="p-2">Code</th>
                                <th className="p-2">Name</th>
                                <th className="p-2">Company</th>
                                <th className="p-2">Department</th>
                                <th className="p-2">Designation</th>
                                <th className="p-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {employees && employees.data.map((emp, index) => (
                                <tr key={emp.id} className="border-b border-gray-500">
                                    <td className="p-2">{index + 1}</td>
                                    <td className="p-2">{emp.code}</td>
                                    <td className="p-2">{emp.name}</td>
                                    <td className="p-2">{emp.company.name}</td>
                                    <td className="p-2">{emp.department.name}</td>
                                    <td className="p-2">{emp.designation.name}</td>
                                    <td className="py-2">
                                        <button
                                            className="px-3 py-1 bg-blue-500 rounded-md hover:bg-blue-600 mr-2"
                                            onClick={() => window.open(route("pdf.documents", { employee: emp.id }), "_blank")}
                                        >
                                            Details
                                        </button>
                                        <button
                                            className="px-3 py-1 bg-green-500 rounded-md hover:bg-green-600 mr-2"
                                            onClick={() => openModal("payslip", emp.id)}
                                        >
                                            Payslip
                                        </button>
                                        <button
                                            className="px-3 py-1 bg-yellow-600 rounded-md hover:bg-yellow-700"
                                            onClick={() => openModal("timings", emp.id)}
                                        >
                                            Timings
                                        </button>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
                <Pagination links={employees.links} />
            </div>

            {modalType && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-4 py-6">
                    <div className="w-full max-w-xl rounded-2xl bg-slate-950 p-6 shadow-2xl shadow-black/40">
                        <div className="flex items-center justify-between gap-4">
                            <div>
                                <h2 className="text-xl font-semibold text-white">{renderModalTitle()}</h2>
                                {modalType !== "attendance" && (
                                    <p className="text-sm text-slate-400">
                                        Employee ID: {modalPayload.employeeId}
                                    </p>
                                )}
                            </div>
                            <button
                                className="rounded-full bg-slate-800 p-2 text-white hover:bg-slate-700"
                                onClick={closeModal}
                                aria-label="Close modal"
                            >
                                ×
                            </button>
                        </div>
                        <div className="mt-5 grid gap-4 sm:grid-cols-2">
                            <div>
                                <label className="mb-2 block text-sm font-medium text-slate-300">Month</label>
                                <select
                                    value={modalPayload.month}
                                    onChange={(e) =>
                                        setModalPayload({
                                            ...modalPayload,
                                            month: Number(e.target.value),
                                        })
                                    }
                                    className="w-full rounded-lg border border-slate-700 bg-slate-900 px-4 py-3 text-white"
                                >
                                    {monthOptions.map((month) => (
                                        <option key={month.value} value={month.value}>
                                            {month.label}
                                        </option>
                                    ))}
                                </select>
                            </div>
                            <div>
                                <label className="mb-2 block text-sm font-medium text-slate-300">Year</label>
                                <select
                                    value={modalPayload.year}
                                    onChange={(e) =>
                                        setModalPayload({
                                            ...modalPayload,
                                            year: Number(e.target.value),
                                        })
                                    }
                                    className="w-full rounded-lg border border-slate-700 bg-slate-900 px-4 py-3 text-white"
                                >
                                    {yearOptions.map((year) => (
                                        <option key={year} value={year}>
                                            {year}
                                        </option>
                                    ))}
                                </select>
                            </div>
                            {modalType === "attendance" && (
                                <div className="sm:col-span-2">
                                    <label className="mb-2 block text-sm font-medium text-slate-300">Company</label>
                                    <select
                                        value={modalPayload.companyId}
                                        onChange={(e) =>
                                            setModalPayload({
                                                ...modalPayload,
                                                companyId: e.target.value,
                                            })
                                        }
                                        className="w-full rounded-lg border border-slate-700 bg-slate-900 px-4 py-3 text-white"
                                    >
                                        {user_companies.map((company) => (
                                            <option key={company.id} value={company.id}>
                                                {company.name}
                                            </option>
                                        ))}
                                    </select>
                                </div>
                            )}
                        </div>
                        <div className="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-end">
                            <button
                                className="rounded-lg bg-slate-700 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-600"
                                onClick={closeModal}
                            >
                                Cancel
                            </button>
                            <button
                                className="rounded-lg bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-500"
                                onClick={handleGenerate}
                            >
                                Generate
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </Layout>
    );
};

export default Reports;
