import { useEffect, useState } from "react";
import Layout from "../Layouts/Layout";
import AddEditModal from "../Components/AddEditModal";
import { PageProps } from "@inertiajs/core";
import toast from "react-hot-toast";
import { format } from "date-fns";
import { router } from "@inertiajs/react";

interface Props extends PageProps {
    departments: {
        id: number;
        name: string;
        is_active: string;
        created_at: string;
        company: {
            id: number;
            name: string;
        }
    }[];
}

const Department = ({ departments, flash, user_companies, auth }: Props) => {
    const [showModal, setShowModal] = useState(false);
    const [editItem, setEditItem] = useState<
        | {
            id: number;
            company_id: number;
            name: string;
            active: boolean;
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
        <Layout role={auth.user?.role} userName={auth.user?.name}>
            <div className="p-4">
                <div className="flex justify-between items-center mb-4">
                    <h1 className="text-2xl text-white font-bold">
                        Departments
                    </h1>
                    <button
                        className="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 cursor-pointer"
                        onClick={() => setShowModal(true)}
                    >
                        Add Department
                    </button>
                </div>
                <div className="bg-gray-700/30 rounded-md shadow-md shadow-gray-500 p-4">
                    <table className="w-full text-left text-white">
                        <thead>
                            <tr className="border-b border-gray-500">
                                <th className="py-2">Name</th>
                                <th className="py-2">Company</th>
                                <th className="py-2">Active</th>
                                <th className="py-2">Created At</th>
                                <th className="py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {departments &&
                                departments.map((dept) => (
                                    <tr key={dept.id}>
                                        <td className="py-2">{dept.name}</td>
                                        <td className="py-2">{dept.company.name}</td>
                                        <td className="py-2">
                                            {dept.is_active === "1" ? "Yes" : "No"}
                                        </td>
                                        <td className="py-2">
                                            {format(
                                                new Date(dept.created_at),
                                                "MMM dd, yyyy hh:mm a",
                                            )}
                                        </td>
                                        <td className="py-2">
                                            <button
                                                className="bg-green-500 text-white px-2 py-1 rounded-md hover:bg-green-600 mr-2 cursor-pointer"
                                                onClick={() => {
                                                    setEditItem({
                                                        id: dept.id,
                                                        company_id: dept.company.id,
                                                        name: dept.name,
                                                        active:
                                                            dept.is_active === "1"
                                                                ? true
                                                                : false,
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
                                                                `/departments/${dept.id}`,
                                                            );
                                                        }}
                                                    >
                                                        Delete
                                                    </button>
                                                )
                                            }
                                        </td>
                                    </tr>
                                ))}
                        </tbody>
                    </table>
                </div>
            </div>
            {/* Add Edit Modal */}
            <AddEditModal
                title={editMode ? "Edit Department" : "Add Department"}
                isOpen={showModal}
                onClose={() => {
                    setShowModal(false)
                    setEditMode(false);
                    setEditItem(undefined);
                }}
                postRoute="/departments"
                editMode={editMode}
                editItem={editItem}
                companies={user_companies}
            />
        </Layout>
    );
};

export default Department;
