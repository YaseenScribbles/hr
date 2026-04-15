import { PageProps } from "@inertiajs/core";
import Layout from "../Layouts/Layout";
import { Fragment, useEffect, useMemo, useState, type FormEvent } from "react";
import { router } from "@inertiajs/react";
import { route } from "ziggy-js";
import toast from "react-hot-toast";

type ShiftOption = {
    id: number;
    code: string;
    description?: string;
};

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

type RosterItem = {
    id: number;
    employee: EmployeeOption;
    shift: ShiftOption;
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
    assigned_days: number;
    shift_codes: string[];
};

interface Props extends PageProps {
    rosters: RosterItem[];
    employees: EmployeeOption[];
    shifts: ShiftOption[];
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

const shiftClasses = [
    "bg-sky-500 text-white",
    "bg-emerald-500 text-white",
    "bg-purple-500 text-white",
    "bg-orange-500 text-white",
    "bg-rose-500 text-white",
    "bg-cyan-500 text-slate-950",
    "bg-yellow-400 text-slate-950",
];

const getShiftColor = (shiftId?: string, shiftCode?: string) => {
    if (shiftCode === 'WH') {
        return "bg-blue-500 text-white";
    }
    if (!shiftId) {
        return "bg-gray-900 text-gray-300";
    }

    const index = Number(shiftId) % shiftClasses.length;
    return shiftClasses[index];
};

const Roster = ({ auth, flash, rosters, employees, shifts, summary, designations, month_days, selected_month, selected_year, selected_designation }: Props) => {
    const [selectedMonth, setSelectedMonth] = useState(selected_month);
    const [selectedYear, setSelectedYear] = useState(selected_year);
    const [selectedDesignation, setSelectedDesignation] = useState(Number(selected_designation));
    const [showModal, setShowModal] = useState(false);
    const [selectedEmployeeIds, setSelectedEmployeeIds] = useState<number[]>([]);
    const [modalEmployeeIds, setModalEmployeeIds] = useState<number[]>([]);
    const [selectedShiftId, setSelectedShiftId] = useState("");
    const [cells, setCells] = useState<Record<number, string>>({});
    const [isDragging, setIsDragging] = useState(false);
    const [isSaving, setIsSaving] = useState(false);
    const [currentPage, setCurrentPage] = useState(1);
    const rowsPerPage = 5;

    const rosterMap = useMemo(() => {
        return rosters.reduce<Record<number, Record<number, { shift_id: number; code: string; description?: string }>>>((acc, roster) => {
            const day = new Date(roster.date).getDate();
            acc[roster.employee.id] = acc[roster.employee.id] || {};
            acc[roster.employee.id][day] = {
                shift_id: roster.shift.id,
                code: roster.shift.code,
                description: roster.shift.description,
            };
            return acc;
        }, {});
    }, [rosters]);

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
            const assignedShiftIds = modalEmployeeIds
                .map((employeeId) => rosterMap[employeeId]?.[monthDay.day]?.shift_id)
                .filter(Boolean);

            if (assignedShiftIds.length === 0) {
                return;
            }

            const allSame = assignedShiftIds.every((shiftId) => shiftId === assignedShiftIds[0]);
            nextCells[monthDay.day] = allSame ? String(assignedShiftIds[0]) : "";
        });

        setCells(nextCells);
    }, [modalEmployeeIds, month_days, rosterMap]);

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
            route("rosters.index"),
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
        setSelectedShiftId("");
        setShowModal(true);
    };

    const handleCellToggle = (day: number) => {
        setCells((prev) => {
            const next = { ...prev };

            if (!selectedShiftId) {
                delete next[day];
                return next;
            }

            if (prev[day] === selectedShiftId) {
                delete next[day];
            } else {
                next[day] = selectedShiftId;
            }

            return next;
        });
    };

    const handleClearRow = () => {
        setCells({});
    };

    const handleSubmit = (e: FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        if (modalEmployeeIds.length === 0) {
            return;
        }

        const assignments = month_days.map((monthDay) => ({
            day: monthDay.day,
            shift_id: cells[monthDay.day] ? Number(cells[monthDay.day]) : null,
        }));

        const payload = {
            employee_ids: modalEmployeeIds.map(String),
            month: String(selectedMonth),
            year: String(selectedYear),
            assignments,
        };

        setIsSaving(true);

        router.post(route("rosters.store"), payload, {
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
                        <h1 className="text-2xl text-white font-bold">Roster Summary</h1>
                        <p className="text-sm text-gray-400 mt-1">
                            {monthNames[selectedMonth - 1]} {selectedYear} roster overview.
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
                            <div>Select employees to assign the same roster pattern.</div>
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
                            Assign roster to selected
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
                                    <th className="py-3 px-3">Assigned Days</th>
                                    <th className="py-3 px-3">Shifts</th>
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
                                        <td className="py-2 px-3">{row.assigned_days}</td>
                                        <td className="py-2 px-3">{row.shift_codes.join(", ") || "-"}</td>
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
                                    Assign roster for {selectedEmployees.length} employee{selectedEmployees.length > 1 ? "s" : ""}
                                </h2>
                                <p className="text-sm text-white mt-1 truncate">
                                    {selectedEmployees.map((employee) => employee.name).join(", ")}
                                </p>
                                <p className="text-sm text-gray-400">
                                    {monthNames[selectedMonth - 1]} {selectedYear} roster grid.
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
                                            Shift
                                            <select
                                                value={selectedShiftId}
                                                onChange={(e) => setSelectedShiftId(e.target.value)}
                                                className="ml-2 rounded-md border border-gray-600 bg-gray-800 px-3 py-2 text-white"
                                            >
                                                <option value="">-- choose shift --</option>
                                                {shifts.map((shift) => (
                                                    <option key={shift.id} value={shift.id}>
                                                        {shift.code} {shift.description ? `- ${shift.description}` : ""}
                                                    </option>
                                                ))}
                                            </select>
                                        </label>
                                        <button
                                            type="button"
                                            className="rounded-md bg-gray-700 px-4 py-2 text-sm text-white hover:bg-gray-600"
                                            onClick={() => setSelectedShiftId("")}
                                        >
                                            Clear selection
                                        </button>
                                    </div>
                                    <div className="mt-4 flex flex-wrap items-center gap-3">
                                        <div className="rounded-full border border-gray-700 bg-gray-800 px-3 py-2 text-sm text-gray-200">
                                            Selected shift: {selectedShiftId ? shifts.find((shift) => String(shift.id) === selectedShiftId)?.code : "None"}
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
                                            if (!window.confirm("Delete selected employee(s) roster for the selected month?")) return;

                                            router.delete(route("rosters.bulkDelete"), {
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
                                    {shifts.map((shift) => (
                                        <div key={shift.id} className="flex flex-wrap justify-center items-center gap-1">
                                            <span className={`inline-flex p-2 h-7 w-7 text-xs items-center justify-center rounded ${getShiftColor(String(shift.id), shift.code)}`}>
                                                {shift.code}
                                            </span>
                                            <div>
                                                <div className="text-xs text-gray-400">{shift.description || "No description"}</div>
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

                                            const shiftId = cells[day.day];
                                            const shift = shifts.find((item) => String(item.id) === shiftId);
                                            const cellClasses = shift
                                                ? `${getShiftColor(shiftId, shift.code)} rounded-xl border border-gray-700 p-3 text-center text-sm font-semibold`
                                                : "rounded-xl border border-gray-700 bg-gray-900 p-3 text-center text-sm text-gray-300 hover:bg-gray-800";

                                            return (
                                                <button
                                                    key={`cell-${day.day}`}
                                                    type="button"
                                                    onMouseDown={() => {
                                                        handleCellToggle(day.day);
                                                        setIsDragging(true);
                                                    }}
                                                    onMouseEnter={() => {
                                                        if (isDragging) {
                                                            handleCellToggle(day.day);
                                                        }
                                                    }}
                                                    className={`${cellClasses} min-h-22.5 transition-colors duration-150`}
                                                >
                                                    <div className="mt-2 text-lg font-semibold">{day.day}</div>
                                                    <div className="mt-2 text-sm">
                                                        {shift ? shift.code : ""}
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
                                        <li>Choose a shift from the dropdown.</li>
                                        <li>Click a date cell or drag across cells to apply the selected shift.</li>
                                        <li>Leave a cell empty to remove a shift assignment.</li>
                                    </ul>
                                </div>
                                <div className="flex flex-col gap-3">
                                    <div className="rounded-xl border border-gray-700 bg-gray-900 p-4 text-sm text-gray-300">
                                        <div className="text-white">Selected employees</div>
                                        <div className="mt-2 text-lg font-semibold">{selectedEmployees.length} selected</div>
                                        <div className="mt-1 text-gray-400">
                                            {selectedEmployees.length > 0
                                                ? `${selectedEmployees.length} employee(s) will receive this roster.`
                                                : "No employees selected"}
                                        </div>
                                    </div>
                                    <button
                                        type="submit"
                                        disabled={isSaving}
                                        className="rounded-md bg-blue-500 px-5 py-3 text-white hover:bg-blue-600 disabled:cursor-not-allowed disabled:opacity-70"
                                    >
                                        Update Roster
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

export default Roster;
