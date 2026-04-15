import { PageProps } from "@inertiajs/core";
import Layout from "../Layouts/Layout";
import { Fragment, useEffect, useMemo, useState, type FormEvent } from "react";
import { router } from "@inertiajs/react";
import { route } from "ziggy-js";
import toast from "react-hot-toast";

type DesignationOption = {
    id: number;
    name: string;
};

type EmployeeOption = {
    id: number;
    name: string;
    des_id?: number;
    designation?: DesignationOption | null;
};

type AttendanceItem = {
    id: number;
    employee: EmployeeOption;
    status?: string | null;
    date: string;
};

type MonthDay = {
    day: number;
    weekday: string;
    weekday_index?: number;
};

type SummaryItem = {
    id: number;
    name: string;
    designation_id?: number;
    designation?: string | null;
    total_days: number;
    present_days: number;
    absent_days: number;
    first_half_absent: number;
    second_half_absent: number;
    holiday_days: number;
};

interface Props extends PageProps {
    attendances: AttendanceItem[];
    employees: EmployeeOption[];
    summary: SummaryItem[];
    designations: DesignationOption[];
    month_days: MonthDay[];
    selected_month: number;
    selected_year: number;
    selected_designation: number;
}

const monthNames = [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December",
];

const statusOptions = [
    { code: "X", label: "Present", classes: "bg-emerald-500 text-white" },
    { code: "A", label: "Absent", classes: "bg-rose-500 text-white" },
    { code: "A/", label: "First half absent", classes: "bg-orange-500 text-white" },
    { code: "/A", label: "Second half absent", classes: "bg-amber-500 text-slate-950" },
    { code: "WH", label: "Holiday", classes: "bg-blue-500 text-white" },
];

const getStatusColor = (status?: string) => {
    switch (status) {
        case "X":
            return "bg-emerald-500 text-white";
        case "A":
            return "bg-rose-500 text-white";
        case "/A":
            return "bg-amber-500 text-slate-950";
        case "A/":
            return "bg-orange-500 text-white";
        case "WH":
            return "bg-blue-500 text-white";
        default:
            return "bg-gray-900 text-gray-300";
    }
};

const Attendance = ({ auth, flash, attendances, employees, summary, designations, month_days, selected_month, selected_year, selected_designation }: Props) => {
    const [selectedMonth, setSelectedMonth] = useState(selected_month);
    const [selectedYear, setSelectedYear] = useState(selected_year);
    const [selectedDesignation, setSelectedDesignation] = useState(Number(selected_designation));
    const [showModal, setShowModal] = useState(false);
    const [selectedEmployeeIds, setSelectedEmployeeIds] = useState<number[]>([]);
    const [modalEmployeeIds, setModalEmployeeIds] = useState<number[]>([]);
    const [selectedStatus, setSelectedStatus] = useState("");
    const [cells, setCells] = useState<Record<number, string>>({});
    const [isDragging, setIsDragging] = useState(false);
    const [isSaving, setIsSaving] = useState(false);
    const [currentPage, setCurrentPage] = useState(1);
    const rowsPerPage = 5;

    const attendanceMap = useMemo(() => {
        return attendances.reduce<Record<number, Record<number, string>>>((acc, attendance) => {
            const day = new Date(attendance.date).getDate();
            acc[attendance.employee.id] = acc[attendance.employee.id] || {};
            if (attendance.status) {
                acc[attendance.employee.id][day] = attendance.status;
            }
            return acc;
        }, {});
    }, [attendances]);

    useEffect(() => {
        const flashData = flash as { toast?: { type: "success" | "error"; message: string } } | undefined;

        if (flashData?.toast) {
            toast[flashData.toast.type](flashData.toast.message);
        }
    }, [flash]);

    useEffect(() => {
        if (modalEmployeeIds.length === 0) {
            setCells({});
            return;
        }

        const nextCells: Record<number, string> = {};

        month_days.forEach((monthDay) => {
            const assignedStatuses = modalEmployeeIds
                .map((employeeId) => attendanceMap[employeeId]?.[monthDay.day] || "")
                .filter(Boolean);

            if (assignedStatuses.length === 0) {
                return;
            }

            const allSame = assignedStatuses.every((status) => status === assignedStatuses[0]);
            nextCells[monthDay.day] = allSame ? assignedStatuses[0] : "";
        });

        setCells(nextCells);
    }, [modalEmployeeIds, month_days, attendanceMap]);

    useEffect(() => {
        const handleMouseUp = () => setIsDragging(false);
        window.addEventListener("mouseup", handleMouseUp);
        return () => window.removeEventListener("mouseup", handleMouseUp);
    }, []);

    const handleFilterChange = (month: number, year: number, designationId?: number) => {
        const nextDesignation = designationId !== undefined ? designationId : selectedDesignation;
        setSelectedMonth(month);
        setSelectedYear(year);
        setSelectedDesignation(nextDesignation);
        setCurrentPage(1);

        router.get(
            route("attendance.index"),
            { month, year, designation_id: nextDesignation },
            { preserveState: true, replace: true },
        );
    };

    const toggleEmployeeSelection = (employeeId: number) => {
        setSelectedEmployeeIds((prev) =>
            prev.includes(employeeId)
                ? prev.filter((id) => id !== employeeId)
                : [...prev, employeeId],
        );
    };

    const handleSelectAll = () => {
        setSelectedEmployeeIds(filteredSummary.map((row) => row.id));
    };

    const handleClearSelection = () => {
        setSelectedEmployeeIds([]);
    };

    const handleOpenModal = (employeeIds: number[]) => {
        setModalEmployeeIds(employeeIds);
        setSelectedStatus("");
        setShowModal(true);
    };

    const isHolidayLocked = (day: number) => {
        return modalEmployeeIds.some((employeeId) => attendanceMap[employeeId]?.[day] === "WH");
    };

    const handleCellToggle = (day: number) => {
        if (isHolidayLocked(day)) {
            return;
        }

        setCells((prev) => {
            const next = { ...prev };

            if (!selectedStatus) {
                delete next[day];
                return next;
            }

            if (prev[day] === selectedStatus) {
                delete next[day];
            } else {
                next[day] = selectedStatus;
            }

            return next;
        });
    };

    const handleClearRow = () => {
        setCells((prev) => {
            const next = { ...prev };

            Object.keys(next).forEach((dayKey) => {
                const day = Number(dayKey);

                if (!isHolidayLocked(day)) {
                    delete next[day];
                }
            });

            return next;
        });
    };

    const handleSubmit = (e: FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        if (modalEmployeeIds.length === 0) {
            return;
        }

        const assignments = month_days.map((monthDay) => ({
            day: monthDay.day,
            status: cells[monthDay.day] ? String(cells[monthDay.day]) : null,
        }));

        const payload = {
            employee_ids: modalEmployeeIds.map(String),
            month: String(selectedMonth),
            year: String(selectedYear),
            assignments,
        };

        setIsSaving(true);

        router.post(route("attendance.store"), payload, {
            preserveScroll: true,
            onStart: () => setIsSaving(true),
            onFinish: () => setIsSaving(false),
            onSuccess: () => {
                setShowModal(false);
                setModalEmployeeIds([]);
                setIsDragging(false);
            },
        });
    };

    const selectedEmployees = employees.filter((employee) => modalEmployeeIds.includes(employee.id));

    const yearOptions = useMemo(() => {
        const currentYear = new Date().getFullYear();
        const years = Array.from({ length: 7 }, (_, index) => currentYear - 3 + index);
        if (!years.includes(selectedYear)) {
            years.push(selectedYear);
            years.sort();
        }
        return years;
    }, [selectedYear]);

    const filteredSummary = useMemo(() => {
        return summary.filter((row) => {
            if (selectedDesignation > 0) {
                return Number(row.designation_id) === selectedDesignation;
            }
            return true;
        });
    }, [summary, selectedDesignation]);

    const pageCount = Math.max(1, Math.ceil(filteredSummary.length / rowsPerPage));

    const paginatedSummary = useMemo(() => {
        const start = (currentPage - 1) * rowsPerPage;
        return filteredSummary.slice(start, start + rowsPerPage);
    }, [filteredSummary, currentPage]);

    const weekdayHeaders = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

    const calendarRows = useMemo(() => {
        const rows: Array<(MonthDay | null)[]> = [];
        const firstWeek: Array<MonthDay | null> = Array(month_days[0]?.weekday_index || 0).fill(null);

        month_days.forEach((monthDay) => {
            firstWeek.push(monthDay);
            if (firstWeek.length === 7) {
                rows.push(firstWeek.slice());
                firstWeek.length = 0;
            }
        });

        if (firstWeek.length > 0) {
            while (firstWeek.length < 7) {
                firstWeek.push(null);
            }
            rows.push(firstWeek);
        }

        return rows;
    }, [month_days]);

    return (
        <Layout role={auth.user?.role}>
            <div className="p-2">
                <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-4">
                    <div>
                        <h1 className="text-2xl text-white font-bold">Attendance Summary</h1>
                        <p className="text-sm text-gray-400 mt-1">
                            {monthNames[selectedMonth - 1]} {selectedYear} attendance overview.
                        </p>
                    </div>
                    <div className="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <label className="text-sm text-gray-300">
                            Month
                            <select
                                value={selectedMonth}
                                onChange={(e) => handleFilterChange(Number(e.target.value), selectedYear)}
                                className="ml-2 rounded-md border border-gray-600 bg-gray-800 px-3 py-2 text-white"
                            >
                                {monthNames.map((name, index) => (
                                    <option key={name} value={index + 1}>
                                        {name}
                                    </option>
                                ))}
                            </select>
                        </label>
                        <label className="text-sm text-gray-300">
                            Year
                            <select
                                value={selectedYear}
                                onChange={(e) => handleFilterChange(selectedMonth, Number(e.target.value))}
                                className="ml-2 rounded-md border border-gray-600 bg-gray-800 px-3 py-2 text-white"
                            >
                                {yearOptions.map((year) => (
                                    <option key={year} value={year}>
                                        {year}
                                    </option>
                                ))}
                            </select>
                        </label>
                        <label className="text-sm text-gray-300">
                            Designation
                            <select
                                value={selectedDesignation}
                                onChange={(e) => handleFilterChange(selectedMonth, selectedYear, Number(e.target.value))}
                                className="ml-2 rounded-md border border-gray-600 bg-gray-800 px-3 py-2 text-white"
                            >
                                <option value={0}>All designations</option>
                                {designations.map((designation) => (
                                    <option key={designation.id} value={designation.id}>
                                        {designation.name}
                                    </option>
                                ))}
                            </select>
                        </label>
                    </div>
                </div>

                <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-4">
                    <div className="text-sm text-gray-300 space-y-1">
                        {selectedEmployeeIds.length > 0 ? (
                            <div>{selectedEmployeeIds.length} employee(s) selected for mass assignment.</div>
                        ) : (
                            <div>Select employees to assign the same attendance pattern.</div>
                        )}
                        <div>
                            Showing {filteredSummary.length} employee(s)
                            {selectedDesignation > 0 && (
                                <span> filtered by designation</span>
                            )}
                            .
                        </div>
                    </div>
                    <div className="flex flex-wrap items-center gap-2">
                        <button
                            type="button"
                            className="rounded-md bg-gray-700 px-4 py-2 text-sm text-white hover:bg-gray-600"
                            onClick={handleSelectAll}
                            disabled={filteredSummary.length === 0}
                        >
                            Select all filtered
                        </button>
                        <button
                            type="button"
                            className="rounded-md bg-gray-700 px-4 py-2 text-sm text-white hover:bg-gray-600"
                            onClick={handleClearSelection}
                        >
                            Clear selection
                        </button>
                        <button
                            type="button"
                            disabled={selectedEmployeeIds.length === 0}
                            className={`rounded-md px-4 py-2 text-sm text-white ${selectedEmployeeIds.length > 0
                                ? "bg-blue-500 hover:bg-blue-600"
                                : "bg-gray-600 cursor-not-allowed"
                                }`}
                            onClick={() => handleOpenModal(selectedEmployeeIds)}
                        >
                            Assign attendance to selected
                        </button>
                    </div>
                </div>

                <div className="bg-gray-700/30 rounded-md shadow-md shadow-gray-500 p-4 mb-6">
                    <div className="overflow-x-auto">
                        <table className="min-w-full text-left text-white">
                            <thead>
                                <tr className="border-b border-gray-500">
                                    <th className="py-3 px-3 w-12">Select</th>
                                    <th className="py-3 px-3">Employee</th>
                                    <th className="py-3 px-3">Designation</th>
                                    <th className="py-3 px-3">Total</th>
                                    <th className="py-3 px-3">Present</th>
                                    <th className="py-3 px-3">Absent</th>
                                    <th className="py-3 px-3">1st Half</th>
                                    <th className="py-3 px-3">2nd Half</th>
                                    <th className="py-3 px-3">Holiday</th>
                                    <th className="py-3 px-3">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {paginatedSummary.map((row) => (
                                    <tr key={row.id} className="border-b border-gray-600 hover:bg-white/5">
                                        <td className="py-2 px-3">
                                            <input
                                                type="checkbox"
                                                checked={selectedEmployeeIds.includes(row.id)}
                                                onChange={() => toggleEmployeeSelection(row.id)}
                                                className="h-4 w-4 rounded border-gray-600 bg-gray-800 text-blue-500"
                                            />
                                        </td>
                                        <td className="py-2 px-3">{row.name}</td>
                                        <td className="py-2 px-3 text-gray-300">{row.designation || "-"}</td>
                                        <td className="py-2 px-3">{row.total_days}</td>
                                        <td className="py-2 px-3">{row.present_days}</td>
                                        <td className="py-2 px-3">{row.absent_days}</td>
                                        <td className="py-2 px-3">{row.first_half_absent}</td>
                                        <td className="py-2 px-3">{row.second_half_absent}</td>
                                        <td className="py-2 px-3">{row.holiday_days}</td>
                                        <td className="py-2 px-3">
                                            <button
                                                className="bg-blue-500 text-white px-3 py-2 rounded-md hover:bg-blue-600"
                                                onClick={() => handleOpenModal([row.id])}
                                            >
                                                View / Edit
                                            </button>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                    <div className="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between text-sm text-gray-300">
                        <div>
                            Page {currentPage} of {pageCount}
                        </div>
                        <div className="flex items-center gap-2">
                            <button
                                type="button"
                                disabled={currentPage === 1}
                                className="rounded-md bg-gray-700 px-4 py-2 text-sm text-white transition hover:bg-gray-600 disabled:cursor-not-allowed disabled:opacity-50"
                                onClick={() => setCurrentPage((prev) => Math.max(prev - 1, 1))}
                            >
                                Previous
                            </button>
                            <button
                                type="button"
                                disabled={currentPage === pageCount}
                                className="rounded-md bg-gray-700 px-4 py-2 text-sm text-white transition hover:bg-gray-600 disabled:cursor-not-allowed disabled:opacity-50"
                                onClick={() => setCurrentPage((prev) => Math.min(prev + 1, pageCount))}
                            >
                                Next
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {showModal && selectedEmployees.length > 0 && (
                <div className="fixed inset-0 z-50 overflow-y-auto bg-black/60 px-4 py-8">
                    <div className="relative mx-auto w-full max-w-6xl rounded-3xl border border-gray-700 bg-gray-900 p-6 shadow-2xl shadow-black/50">
                        <button
                            type="button"
                            className="absolute right-2 top-2 inline-flex h-10 w-10 items-center justify-center rounded-full border border-gray-700 bg-gray-800 text-xl text-white transition hover:bg-gray-700"
                            onClick={() => {
                                setShowModal(false);
                                setModalEmployeeIds([]);
                                setIsDragging(false);
                            }}
                        >
                            ×
                        </button>
                        <div className="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                            <div className="self-start flex flex-col gap-1 max-w-1/4">
                                <h2 className="text-xl font-semibold text-white">
                                    Assign attendance for {selectedEmployees.length} employee{selectedEmployees.length > 1 ? "s" : ""}
                                </h2>
                                <p className="text-sm text-white mt-1 truncate">
                                    {selectedEmployees.map((employee) => employee.name).join(", ")}
                                </p>
                                <p className="text-sm text-gray-400">
                                    {monthNames[selectedMonth - 1]} {selectedYear} attendance grid.
                                </p>
                            </div>
                            <div className="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between mb-6">
                                <div>
                                    <div className="flex flex-wrap items-center gap-3">
                                        <label className="text-sm text-gray-300">
                                            Month
                                            <select
                                                value={selectedMonth}
                                                onChange={(e) => handleFilterChange(Number(e.target.value), selectedYear)}
                                                className="ml-2 rounded-md border border-gray-600 bg-gray-800 px-3 py-2 text-white"
                                            >
                                                {monthNames.map((name, index) => (
                                                    <option key={name} value={index + 1}>
                                                        {name}
                                                    </option>
                                                ))}
                                            </select>
                                        </label>
                                        <label className="text-sm text-gray-300">
                                            Year
                                            <select
                                                value={selectedYear}
                                                onChange={(e) => handleFilterChange(selectedMonth, Number(e.target.value))}
                                                className="ml-2 rounded-md border border-gray-600 bg-gray-800 px-3 py-2 text-white"
                                            >
                                                {yearOptions.map((year) => (
                                                    <option key={year} value={year}>
                                                        {year}
                                                    </option>
                                                ))}
                                            </select>
                                        </label>
                                        <label className="text-sm text-gray-300">
                                            Status
                                            <select
                                                value={selectedStatus}
                                                onChange={(e) => setSelectedStatus(e.target.value)}
                                                className="ml-2 rounded-md border border-gray-600 bg-gray-800 px-3 py-2 text-white"
                                            >
                                                <option value="">-- choose status --</option>
                                                {statusOptions.map((status) => (
                                                    <option key={status.code} value={status.code}>
                                                        {status.code} - {status.label}
                                                    </option>
                                                ))}
                                            </select>
                                        </label>
                                        <button
                                            type="button"
                                            className="rounded-md bg-gray-700 px-4 py-2 text-sm text-white hover:bg-gray-600"
                                            onClick={() => setSelectedStatus("")}
                                        >
                                            Clear selection
                                        </button>
                                    </div>
                                    <div className="mt-4 flex flex-wrap items-center gap-3">
                                        <div className="rounded-full border border-gray-700 bg-gray-800 px-3 py-2 text-sm text-gray-200">
                                            Selected status: {selectedStatus || "None"}
                                        </div>
                                        <div className="rounded-full border border-gray-700 bg-gray-800 px-3 py-2 text-sm text-gray-200">
                                            Assigned: {Object.values(cells).filter(Boolean).length} days
                                        </div>
                                    </div>
                                </div>
                                <div className="flex flex-wrap items-center gap-3 justify-end">
                                    <button
                                        type="button"
                                        className="rounded-md bg-amber-500 px-4 py-2 text-sm text-slate-950 hover:bg-amber-400"
                                        onClick={handleClearRow}
                                    >
                                        Clear all days
                                    </button>
                                    <button
                                        type="button"
                                        className="rounded-md bg-red-700 px-4 py-2 text-sm text-white hover:bg-red-800"
                                        onClick={() => {
                                            if (modalEmployeeIds.length === 0) return;
                                            if (!window.confirm("Delete selected employee(s) attendance for the selected month?")) return;

                                            router.delete(route("attendance.bulkDelete"), {
                                                data: {
                                                    employee_ids: modalEmployeeIds.map(String),
                                                    month: selectedMonth,
                                                    year: selectedYear,
                                                },
                                                preserveScroll: true,
                                                onSuccess: () => {
                                                    setShowModal(false);
                                                    setModalEmployeeIds([]);
                                                    setIsDragging(false);
                                                },
                                            });
                                        }}
                                    >
                                        Delete month
                                    </button>
                                </div>
                            </div>

                            <div className="rounded-3xl border border-gray-700 bg-gray-950/60 p-4 text-sm text-gray-300 self-start">
                                <div className="grid gap-1 sm:grid-cols-2 lg:grid-cols-3">
                                    {statusOptions.map((status) => (
                                        <div key={status.code} className="flex flex-col justify-center items-center gap-1 mr-2">
                                            <span className={`inline-flex p-2 h-7 w-7 text-xs items-center justify-center rounded ${status.classes}`}>
                                                {status.code}
                                            </span>
                                            <div className="w-20 text-center">
                                                <div className="text-xs text-gray-400 truncate">{status.label}</div>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </div>

                        <div className="overflow-x-auto rounded-xl border border-gray-700 bg-gray-950/80 p-4 mb-4">
                            <div className="grid grid-cols-7 gap-1 text-xs uppercase tracking-wide text-gray-400">
                                {weekdayHeaders.map((weekday) => (
                                    <div key={weekday} className="rounded-md bg-gray-900 p-2 text-center font-semibold">
                                        {weekday}
                                    </div>
                                ))}
                            </div>
                            <div className="mt-2 grid grid-cols-7 gap-1">
                                {calendarRows.map((week, weekIndex) => (
                                    <Fragment key={`week-${weekIndex}`}>
                                        {week.map((day, dayIndex) => {
                                            if (!day) {
                                                return <div key={`empty-${weekIndex}-${dayIndex}`} className="min-h-22.5 rounded-xl border border-gray-700 bg-gray-900" />;
                                            }

                                            const status = cells[day.day];
                                            const cellClasses = status
                                                ? `${getStatusColor(status)} rounded-xl border border-gray-700 p-3 text-center text-sm font-semibold`
                                                : "rounded-xl border border-gray-700 bg-gray-900 p-3 text-center text-sm text-gray-300 hover:bg-gray-800";

                                            const holidayLocked = isHolidayLocked(day.day);

                                            return (
                                                <button
                                                    key={`cell-${day.day}`}
                                                    type="button"
                                                    disabled={holidayLocked}
                                                    onMouseDown={() => {
                                                        if (holidayLocked) return;
                                                        handleCellToggle(day.day);
                                                        setIsDragging(true);
                                                    }}
                                                    onMouseEnter={() => {
                                                        if (isDragging && !holidayLocked) {
                                                            handleCellToggle(day.day);
                                                        }
                                                    }}
                                                    className={`${cellClasses} min-h-22.5 transition-colors duration-150 ${holidayLocked ? "cursor-not-allowed" : ""}`}
                                                >
                                                    <div className="mt-2 text-lg font-semibold">{day.day}</div>
                                                    <div className="mt-2 text-sm">
                                                        {status || ""}
                                                    </div>
                                                </button>
                                            );
                                        })}
                                    </Fragment>
                                ))}
                            </div>
                        </div>

                        <form onSubmit={handleSubmit} className="space-y-4">
                            <div className="grid gap-4 lg:grid-cols-[1fr_auto]">
                                <div className="rounded-xl border border-gray-700 bg-gray-900 p-4 text-sm text-gray-300">
                                    <div className="mb-3 text-white">Instructions</div>
                                    <ul className="space-y-2 list-disc pl-5">
                                        <li>Choose an attendance status from the dropdown.</li>
                                        <li>Click a date cell or drag across cells to apply the selected status.</li>
                                        <li>Leave a cell empty to remove attendance for that day.</li>
                                    </ul>
                                </div>
                                <div className="flex flex-col gap-3">
                                    <div className="rounded-xl border border-gray-700 bg-gray-900 p-4 text-sm text-gray-300">
                                        <div className="text-white">Selected employees</div>
                                        <div className="mt-2 text-lg font-semibold">{selectedEmployees.length} selected</div>
                                        <div className="mt-1 text-gray-400">
                                            {selectedEmployees.length > 0
                                                ? `${selectedEmployees.length} employee(s) will receive this attendance pattern.`
                                                : "No employees selected"}
                                        </div>
                                    </div>
                                    <button
                                        type="submit"
                                        disabled={isSaving}
                                        className="rounded-md bg-blue-500 px-5 py-3 text-white hover:bg-blue-600 disabled:cursor-not-allowed disabled:opacity-70"
                                    >
                                        Update Attendance
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </Layout>
    );
};

export default Attendance;
