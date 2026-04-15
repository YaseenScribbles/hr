import { useEffect, useState } from "react";
import Layout from "../Layouts/Layout"
import { PageProps } from "@inertiajs/core"
import toast from "react-hot-toast";
import { format } from "date-fns";
import { router } from "@inertiajs/react";

interface Props extends PageProps {
    defaults: {
        id: number;
        key: string;
        value: string;
        created_at: string;
    }[];
}

const Defaults = ({ auth, flash, defaults }: Props) => {

    const [showModal, setShowModal] = useState(false);
    const [editItem, setEditItem] = useState<
        | {
            id: number;
            key: string;
            value: string;
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
                        Defaults
                    </h1>
                    <button
                        className="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 cursor-pointer"
                        onClick={() => setShowModal(true)}
                    >
                        Add Default
                    </button>
                </div>
                <div className="bg-gray-700/30 rounded-md shadow-md shadow-gray-500 p-4">
                    <table className="w-full text-left text-white">
                        <thead>
                            <tr className="border-b border-gray-500">
                                <th className="py-2">Key</th>
                                <th className="py-2">Value</th>
                                <th className="py-2">Created At</th>
                                <th className="py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {defaults &&
                                defaults.map((def) => (
                                    <tr key={def.id}>
                                        <td className="py-2">{def.key}</td>
                                        <td className="py-2">{def.value}</td>
                                        <td className="py-2">
                                            {format(
                                                new Date(def.created_at),
                                                "MMM dd, yyyy hh:mm a",
                                            )}
                                        </td>
                                        <td className="py-2">
                                            <button
                                                className="bg-green-500 text-white px-2 py-1 rounded-md hover:bg-green-600 mr-2 cursor-pointer"
                                                onClick={() => {
                                                    setEditItem({
                                                        id: def.id,
                                                        key: def.key,
                                                        value: def.value,
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
                                                                `/defaults/${def.id}`,
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
            <Modal
                show={showModal}
                onClose={() => {
                    setShowModal(false);
                    setEditMode(false);
                    setEditItem(undefined);
                }}
                onSubmit={(data: { key: string; value: string }) => {
                    if (editMode && editItem) {
                        router.post(`/defaults/${editItem.id}?_method=PUT`, data, {
                            onSuccess: () => {
                                setShowModal(false);
                                setEditMode(false);
                                setEditItem(undefined);
                            },
                            onError: (errors) => {
                                if (errors && Object.keys(errors).length > 0) {
                                    Object.values(errors).forEach((msg) => {
                                        const message = Array.isArray(msg) ? msg[0] : msg;

                                        toast.error(message as string);
                                    });
                                }
                            }
                        });
                    } else {
                        router.post("/defaults", data, {
                            onSuccess: () => {
                                setShowModal(false);
                                setEditMode(false);
                                setEditItem(undefined);
                            },
                            onError: (errors) => {
                                if (errors && Object.keys(errors).length > 0) {
                                    Object.values(errors).forEach((msg) => {
                                        const message = Array.isArray(msg) ? msg[0] : msg;

                                        toast.error(message as string);
                                    });
                                }
                            }
                        });
                    }
                }}
                editMode={editMode}
                editItem={editItem}
            />
        </Layout>
    )
}

export default Defaults


const Modal = ({ show, onClose, onSubmit, editMode, editItem }: any) => {
    const [key, setKey] = useState("");
    const [value, setValue] = useState("");

    useEffect(() => {
        if (editMode && editItem) {
            setKey(editItem.key);
            setValue(editItem.value);
        } else {
            setKey("");
            setValue("");
        }
    }, [editMode, editItem]);

    if (!show) return null;

    return (
        <div className="fixed inset-0 bg-gray-900/50 flex items-center justify-center">
            <div className="bg-gray-800 rounded-md shadow-md shadow-gray-500 p-6 w-96 max-h-3/4">
                <h2 className="text-xl text-white mb-4">
                    {editMode ? "Edit Default" : "Add Default"}
                </h2>
                <form
                    onSubmit={(e) => {
                        e.preventDefault();
                        onSubmit({ key, value });
                    }}
                >
                    <div className="mb-4">
                        <label className="block text-gray-300 mb-1">Key</label>
                        <input
                            type="text"
                            className="w-full px-3 py-2 rounded-md bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value={key}
                            onChange={(e) => setKey(e.target.value)}
                            required
                        />
                    </div>
                    <div className="mb-4">
                        <label className="block text-gray-300 mb-1">Value</label>
                        <input
                            type="text"
                            className="w-full px-3 py-2 rounded-md bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value={value}
                            onChange={(e) => setValue(e.target.value)}
                        />
                    </div>
                    <div className="flex justify-end">
                        <button
                            type="button"
                            className="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 mr-2"
                            onClick={onClose}
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            className="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600"
                        >
                            {editMode ? "Update" : "Create"}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    );
}
