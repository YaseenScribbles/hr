import { useEffect, useState } from "react";
import Layout from "../Layouts/Layout"
import { PageProps } from "@inertiajs/core"
import toast, { Toaster } from "react-hot-toast";
import { format } from "date-fns";
import { router, useForm } from "@inertiajs/react";
import { extractHourAndMinutes, formatTime } from "../Helpers/Functions";

interface Props extends PageProps {
    shifts: Shift[]
}

type Shift = {
    id: number;
    company_id: string;
    code: string;
    description: string;
    login: string;
    login_min: string;
    login_max: string;
    logout: string;
    logout_min: string;
    logout_max: string;
    lunch_in: string;
    lunch_in_min: string;
    lunch_in_max: string;
    lunch_out: string;
    lunch_out_min: string;
    lunch_out_max: string;
    ot_in: string;
    ot_in_min: string;
    ot_in_max: string;
    ot_out: string;
    ot_out_min: string;
    ot_out_max: string;
    company: {
        id: number;
        name: string;
    }
    created_at: string;
}

const ShiftMaster = ({ auth, flash, shifts, user_companies }: Props) => {
    const [showModal, setShowModal] = useState(false);
    const [editMode, setEditMode] = useState(false);
    const [editItem, setEditItem] = useState<Shift | undefined>(undefined)

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
                        Shift Master
                    </h1>
                    <button
                        className="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 cursor-pointer"
                        onClick={() => setShowModal(true)}
                    >
                        Add Shift
                    </button>
                </div>
                <div className="bg-gray-700/30 rounded-md shadow-md shadow-gray-500 p-4">
                    <table className="w-full text-left text-white">
                        <thead>
                            <tr className="border-b border-gray-500">
                                <th className="py-2">Company</th>
                                <th className="py-2">Code</th>
                                <th className="py-2">Desc</th>
                                <th className="py-2">Log In</th>
                                <th className="py-2">Log Out</th>
                                <th className="py-2">Lunch In</th>
                                <th className="py-2">Lunch Out</th>
                                <th className="py-2">OT. In</th>
                                <th className="py-2">OT. Out</th>
                                <th className="py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {
                                shifts && shifts.map(shift => <tr key={shift.id}>
                                    <td className="py-2">{shift.company.name}</td>
                                    <td className="py-2">{shift.code}</td>
                                    <td className="py-2">{shift.description}</td>
                                    <td className="py-2">
                                        {formatTime(shift.login)}
                                    </td>
                                    <td className="py-2">
                                        {formatTime(shift.logout)}
                                    </td >
                                    <td className="py-2">
                                        {formatTime(shift.lunch_in)}
                                    </td>
                                    <td className="py-2">
                                        {formatTime(shift.lunch_out)}
                                    </td>
                                    <td className="py-2">
                                        {formatTime(shift.ot_in)}
                                    </td>
                                    <td className="py-2">
                                        {formatTime(shift.ot_out)}
                                    </td>
                                    <td className="py-2">
                                        <button
                                            className="bg-green-500 text-white px-2 py-1 rounded-md hover:bg-green-600 mr-2 cursor-pointer"
                                            onClick={() => {
                                                setEditItem(shift);
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
                                                            `/shifts/${shift.id}`,
                                                        );
                                                    }}
                                                >
                                                    Delete
                                                </button>
                                            )}
                                    </td>
                                </tr>)
                            }
                        </tbody>
                    </table>
                </div>
            </div>
            <Modal
                isOpen={showModal}
                onClose={() => {
                    setEditItem(undefined);
                    setEditMode(false);
                    setShowModal(false);
                }}
                editMode={editMode}
                editItem={editItem}
                companies={user_companies}
            />
        </Layout>
    )
}

export default ShiftMaster

interface ModalProps {
    isOpen: boolean;
    onClose: () => void;
    editMode: boolean;
    editItem?: Shift;
    companies: {
        id: number;
        name: string;
    }[];
}

export const Modal = ({
    isOpen,
    editMode,
    onClose,
    editItem,
    companies,
}: ModalProps) => {

    if (!isOpen) return null;

    const { data, setData, post, errors, processing } = useForm({
        company_id: "",
        code: "",
        description: "",
        login: "",
        login_min: "",
        login_max: "",
        logout: "",
        logout_min: "",
        logout_max: "",
        lunch_in: "",
        lunch_in_min: "",
        lunch_in_max: "",
        lunch_out: "",
        lunch_out_min: "",
        lunch_out_max: "",
        ot_in: "",
        ot_in_min: "",
        ot_in_max: "",
        ot_out: "",
        ot_out_min: "",
        ot_out_max: "",
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        if (editMode && editItem) {
            post(`shifts/${editItem.id}?_method=put`, {
                onSuccess: () => {
                    onClose();
                },
            });
        } else {
            // Create logic here (e.g., post request)
            // post('/departments', data);
            post("shifts", {
                onSuccess: () => {
                    onClose();
                },
            });
        }
    };

    useEffect(() => {
        if (editMode && editItem) {
            setData({
                company_id: editItem.company_id,
                code: editItem.code,
                description: editItem.description ?? "",
                login: extractHourAndMinutes(editItem.login),
                login_min: extractHourAndMinutes(editItem.login_min),
                login_max: extractHourAndMinutes(editItem.login_max),
                logout: extractHourAndMinutes(editItem.logout),
                logout_min: extractHourAndMinutes(editItem.logout_min),
                logout_max: extractHourAndMinutes(editItem.logout_max),
                lunch_in: extractHourAndMinutes(editItem.lunch_in),
                lunch_in_min: extractHourAndMinutes(editItem.lunch_in_min),
                lunch_in_max: extractHourAndMinutes(editItem.lunch_in_max),
                lunch_out: extractHourAndMinutes(editItem.lunch_out),
                lunch_out_min: extractHourAndMinutes(editItem.lunch_out_min),
                lunch_out_max: extractHourAndMinutes(editItem.lunch_out_max),
                ot_in: extractHourAndMinutes(editItem.ot_in),
                ot_in_min: extractHourAndMinutes(editItem.ot_in_min),
                ot_in_max: extractHourAndMinutes(editItem.ot_in_max),
                ot_out: extractHourAndMinutes(editItem.ot_out),
                ot_out_min: extractHourAndMinutes(editItem.ot_out_min),
                ot_out_max: extractHourAndMinutes(editItem.ot_out_max),
            });
        } else {
            setData({
                company_id: "",
                code: "",
                description: "",
                login: "",
                login_min: "",
                login_max: "",
                logout: "",
                logout_min: "",
                logout_max: "",
                lunch_in: "",
                lunch_in_min: "",
                lunch_in_max: "",
                lunch_out: "",
                lunch_out_min: "",
                lunch_out_max: "",
                ot_in: "",
                ot_in_min: "",
                ot_in_max: "",
                ot_out: "",
                ot_out_min: "",
                ot_out_max: "",
            });
        }
    }, [editMode, editItem]);

    useEffect(() => {
        if (errors && Object.keys(errors).length > 0) {
            Object.values(errors).forEach((msg) => {
                const message = Array.isArray(msg) ? msg[0] : msg;

                toast.error(message as string, {
                    toasterId: "shiftModal"
                });
            });
        }
    }, [errors]);

    return (
        <div className="fixed inset-0 bg-gray-900/50 flex items-center justify-center">
            <div className="bg-gray-800 rounded-md shadow-md shadow-gray-500 p-6 w-2/4 max-h-3/4">
                <div className="flex justify-between items-center mb-4 sticky top-0">
                    <h2 className="text-xl font-bold text-white">
                        {editMode ? "Edit Shift" : "Add Shift"}
                    </h2>
                    <button
                        className="text-white text-4xl hover:text-gray-300 cursor-pointer"
                        onClick={onClose}
                    >
                        &times;
                    </button>
                </div>
                <form onSubmit={handleSubmit} className="h-100 2xl:h-125 overflow-auto space-y-6 p-1">

                    {/* Company */}
                    <div>
                        <label className="text-gray-300 text-sm">Company</label>
                        <select
                            value={data.company_id}
                            onChange={(e) => setData("company_id", e.target.value)}
                            className="w-full mt-1 p-2 rounded bg-gray-700 text-white"
                        >
                            <option value="">Select Company</option>
                            {companies.map((c: any) => (
                                <option key={c.id} value={c.id}>
                                    {c.name}
                                </option>
                            ))}
                        </select>
                        {errors.company_id && <p className="text-red-400 text-xs">{errors.company_id}</p>}
                    </div>

                    {/* Code + Description */}
                    <div className="grid grid-cols-2 gap-4">
                        <div>
                            <label className="text-gray-300 text-sm">Code</label>
                            <input
                                type="text"
                                value={data.code}
                                onChange={(e) => setData("code", e.target.value)}
                                className="w-full mt-1 p-2 rounded bg-gray-700 text-white"
                            />
                            {errors.code && <p className="text-red-400 text-xs">{errors.code}</p>}
                        </div>

                        <div>
                            <label className="text-gray-300 text-sm">Description</label>
                            <input
                                type="text"
                                value={data.description}
                                onChange={(e) => setData("description", e.target.value)}
                                className="w-full mt-1 p-2 rounded bg-gray-700 text-white"
                            />
                        </div>
                    </div>

                    {/* Reusable Time Group */}
                    {[
                        { label: "Login", key: "login" },
                        { label: "Logout", key: "logout" },
                        { label: "Lunch In", key: "lunch_in" },
                        { label: "Lunch Out", key: "lunch_out" },
                        { label: "OT In", key: "ot_in" },
                        { label: "OT Out", key: "ot_out" },
                    ].map((field) => (
                        <div key={field.key}>
                            <p className="text-gray-200 font-semibold mb-2">{field.label}</p>

                            <div className="grid grid-cols-3 gap-4">
                                {/* Time */}
                                <input
                                    type="time"
                                    value={(data as any)[field.key]}
                                    onChange={(e) => setData(field.key as any, e.target.value)}
                                    className="p-2 rounded bg-gray-700 text-white"
                                />

                                {/* Min */}
                                <input
                                    type="time"
                                    value={(data as any)[`${field.key}_min`]}
                                    onChange={(e) =>
                                        setData(`${field.key}_min` as any, e.target.value)
                                    }
                                    className="p-2 rounded bg-gray-700 text-white"
                                    placeholder="Min"
                                />

                                {/* Max */}
                                <input
                                    type="time"
                                    value={(data as any)[`${field.key}_max`]}
                                    onChange={(e) =>
                                        setData(`${field.key}_max` as any, e.target.value)
                                    }
                                    className="p-2 rounded bg-gray-700 text-white"
                                    placeholder="Max"
                                />
                            </div>
                        </div>
                    ))}

                    {/* Submit */}
                    <div className="flex justify-end gap-3 pt-4">
                        <button
                            type="button"
                            onClick={onClose}
                            className="px-4 py-2 bg-gray-600 text-white rounded cursor-pointer hover:bg-gray-700"
                        >
                            Cancel
                        </button>

                        <button
                            type="submit"
                            disabled={processing}
                            className="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded cursor-pointer"
                        >
                            {processing ? "Saving..." : editMode ? "Update" : "Create"}
                        </button>
                    </div>
                </form>
            </div>
            <Toaster position="bottom-right" toasterId="shiftModal" />
        </div>
    )
}

