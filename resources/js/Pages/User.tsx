import React, { useEffect, useState } from "react";
import Layout from "../Layouts/Layout";
import { PageProps } from "@inertiajs/core";
import { router, useForm } from "@inertiajs/react";
import toast from "react-hot-toast";

type User = {
    id: number;
    name: string;
    email: string;
    role: string;
    is_active: string;
    companies: {
        id: number;
        name: string;
    }[];
};

interface Props extends PageProps {
    users: User[];
    companies: {
        id: number;
        name: string;
    }[];
}

const User = ({ users, flash, companies, auth }: Props) => {
    const [showModal, setShowModal] = useState(false);
    const [editMode, setEditMode] = useState(false);
    const [editItem, setEditItem] = useState<User | undefined>(undefined);

    useEffect(() => {
        if (flash?.toast) {
            toast[flash.toast.type](flash.toast.message);
        }
    }, [flash]);

    return (
        <Layout role={auth.user!.role}>
            <div className="p-4">
                <div className="flex justify-between items-center mb-4">
                    <h1 className="text-2xl text-white font-bold">Users</h1>
                    <button
                        className="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 cursor-pointer"
                        onClick={() => setShowModal(true)}
                    >
                        Add User
                    </button>
                </div>
                <div className="bg-gray-700/30 rounded-md shadow-md shadow-gray-500 p-4">
                    <table className="w-full text-left text-white">
                        <thead>
                            <tr className="border-b border-gray-500">
                                <th className="py-2">Name</th>
                                <th className="py-2">Email</th>
                                <th className="py-2">Role</th>
                                <th className="py-2">Active</th>
                                <th className="py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {users &&
                                users.map((user) => (
                                    <tr key={user.id}>
                                        <td className="py-2">{user.name}</td>
                                        <td className="py-2">{user.email}</td>
                                        <td className="py-2">
                                            {user.role.charAt(0).toUpperCase() +
                                                user.role.slice(1)}
                                        </td>
                                        <td className="py-2">
                                            {user.is_active === "1"
                                                ? "Yes"
                                                : "No"}
                                        </td>
                                        <td className="py-2">
                                            <button
                                                className="bg-green-500 text-white px-2 py-1 rounded-md hover:bg-green-600 mr-2 cursor-pointer"
                                                onClick={() => {
                                                    setEditItem(user);
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
                                                        `/users/${user.id}`,
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
            </div>
            <Modal
                editMode={editMode}
                isOpen={showModal}
                onClose={() => {
                    setEditItem(undefined);
                    setEditMode(false);
                    setShowModal(false);
                }}
                editItem={editItem}
                companies={companies}
            />
        </Layout>
    );
};

export default User;

interface ModalProps {
    isOpen: boolean;
    onClose: () => void;
    editMode: boolean;
    editItem?: User;
    companies: {
        id: number;
        name: string;
    }[];
}

const Modal = ({
    isOpen,
    editMode,
    onClose,
    editItem,
    companies,
}: ModalProps) => {
    if (!isOpen) return null;

    const { data, setData, post, errors, processing } = useForm({
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
        role: "user",
        is_active: true,
        selected_companies: [] as number[],
    });

    function toggleCompany(id: number) {
        if (data.selected_companies.includes(id)) {
            // setSelectedCompanies(selectedCompanies.filter((c) => c !== id));
            setData(
                "selected_companies",
                data.selected_companies.filter((c) => c !== id),
            );
        } else {
            setData("selected_companies", [...data.selected_companies, id]);
        }
    }

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        if (editMode && editItem) {
            // Update logic here (e.g., put request)
            // post(`/departments/${editItem.id}`, { ...data, _method: 'put' });
            post(`users/${editItem.id}?_method=put`, {
                onSuccess: () => {
                    onClose();
                },
            });
        } else {
            // Create logic here (e.g., post request)
            // post('/departments', data);
            post("users", {
                onSuccess: () => {
                    onClose();
                },
            });
        }
    };

    useEffect(() => {
        if (editMode && editItem) {
            setData({
                name: editItem.name,
                email: editItem.email,
                password: "",
                password_confirmation: "",
                role: editItem.role,
                is_active: editItem.is_active === "1" ? true : false,
                selected_companies: editItem.companies.map((c) => c.id),
            });
        } else {
            setData({
                name: "",
                email: "",
                password: "",
                password_confirmation: "",
                role: "user",
                is_active: true,
                selected_companies: [],
            });
        }
    }, [editMode, editItem]);

    return (
        <div className="fixed inset-0 bg-gray-900/50 flex items-center justify-center">
            <div className="bg-gray-800 rounded-md shadow-md shadow-gray-500 p-6 w-96 max-h-3/4">
                <div className="flex justify-between items-center mb-4 sticky top-0">
                    <h2 className="text-xl font-bold text-white">
                        {editMode ? "Edit User" : "Add User"}
                    </h2>
                    <button
                        className="text-white text-4xl hover:text-gray-300 cursor-pointer"
                        onClick={onClose}
                    >
                        &times;
                    </button>
                </div>
                <form onSubmit={handleSubmit} className="h-100 2xl:h-125 overflow-auto">

                    <div className="mb-4">
                        <label
                            className="block text-gray-300 mb-2"
                            htmlFor="name"
                        >
                            Name
                        </label>
                        <input
                            type="text"
                            id="name"
                            className="w-full rounded p-2 bg-gray-600 text-white placeholder:text-gray-400 border border-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter name"
                            value={data.name}
                            onChange={(e) => setData("name", e.target.value)}
                        />
                        {errors.name && (
                            <p className="text-red-500 text-sm mt-1">
                                {errors.name}
                            </p>
                        )}
                    </div>
                    <div className="mb-4">
                        <label
                            className="block text-gray-300 mb-2"
                            htmlFor="email"
                        >
                            Email
                        </label>
                        <input
                            type="email"
                            id="email"
                            className="w-full rounded p-2 bg-gray-600 text-white placeholder:text-gray-400 border border-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter email"
                            value={data.email}
                            onChange={(e) => setData("email", e.target.value)}
                        />
                        {errors.email && (
                            <p className="text-red-500 text-sm mt-1">
                                {errors.email}
                            </p>
                        )}
                    </div>
                    <div className="mb-4">
                        <label
                            className="block text-gray-300 mb-2"
                            htmlFor="password"
                        >
                            Password
                        </label>
                        <input
                            type="password"
                            id="password"
                            className="w-full rounded p-2 bg-gray-600 text-white placeholder:text-gray-400 border border-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter password"
                            value={data.password}
                            onChange={(e) =>
                                setData("password", e.target.value)
                            }
                        />
                        {errors.password && (
                            <p className="text-red-500 text-sm mt-1">
                                {errors.password}
                            </p>
                        )}
                    </div>
                    <div className="mb-4">
                        <label
                            className="block text-gray-300 mb-2"
                            htmlFor="password_confirmation"
                        >
                            Confirm Password
                        </label>
                        <input
                            type="password"
                            id="password_confirmation"
                            className="w-full rounded p-2 bg-gray-600 text-white placeholder:text-gray-400 border border-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter password again"
                            value={data.password_confirmation}
                            onChange={(e) =>
                                setData("password_confirmation", e.target.value)
                            }
                        />
                        {errors.password_confirmation && (
                            <p className="text-red-500 text-sm mt-1">
                                {errors.password_confirmation}
                            </p>
                        )}
                    </div>
                    <div className="mb-4">
                        <label
                            className="block text-gray-300 mb-2"
                            htmlFor="role"
                        >
                            Role
                        </label>
                        <select
                            id="role"
                            className="w-full rounded p-2 bg-gray-600 text-white placeholder:text-gray-400 border border-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value={data.role}
                            onChange={(e) => setData("role", e.target.value)}
                        >
                            <option value="admin">Administrator</option>
                            <option value="user">User</option>
                        </select>
                    </div>
                    {editMode && editItem && (
                        <>
                            <div className="mb-4">
                                <label
                                    className="block text-gray-300 mb-2"
                                    htmlFor="companies"
                                >
                                    Companies{" "}
                                    <span className="text-xs text-gray-400">
                                        (* Hold ctrl key to multi select)
                                    </span>
                                </label>
                                <div className="border border-gray-500 rounded p-2 bg-gray-600">
                                    <div className="flex flex-wrap gap-2 mb-2">
                                        {data.selected_companies.map((id) => {
                                            const company = companies.find(
                                                (c) => c.id === id,
                                            );
                                            return (
                                                <span
                                                    key={id}
                                                    className="flex items-center gap-1 bg-blue-500 text-white px-2 py-1 rounded"
                                                >
                                                    {company?.name}{" "}
                                                    <button
                                                        className="flex items-center bg-white text-gray-800 text-sm px-2 rounded-full cursor-pointer hover:bg-blue-500 hover:border hover:border-white hover:text-white transition duration-150"
                                                        onClick={() =>
                                                            toggleCompany(id)
                                                        }
                                                    >
                                                        &times;
                                                    </button>
                                                </span>
                                            );
                                        })}
                                    </div>

                                    <div className="max-h-40 overflow-y-auto transition-all duration-150">
                                        {companies
                                            .filter(
                                                (company) =>
                                                    !data.selected_companies.includes(
                                                        company.id,
                                                    ),
                                            )
                                            .map((company) => (
                                                <div
                                                    key={company.id}
                                                    className="cursor-pointer hover:bg-gray-500 p-1 text-white transition-all duration-150"
                                                    onClick={() =>
                                                        toggleCompany(
                                                            company.id,
                                                        )
                                                    }
                                                >
                                                    {company.name}
                                                </div>
                                            ))}
                                    </div>
                                </div>
                            </div>
                            <div className="flex items-center mb-4">
                                <input
                                    type="checkbox"
                                    id="active"
                                    className="h-4 w-4 text-blue-600 bg-gray-700 border-gray-500 focus:ring-blue-500 focus:ring-2 mr-2"
                                    checked={data.is_active}
                                    onChange={(e) =>
                                        setData("is_active", e.target.checked)
                                    }
                                />
                                <label
                                    className="text-gray-300"
                                    htmlFor="active"
                                >
                                    Active
                                </label>
                            </div>
                        </>
                    )}
                    <button
                        type="submit"
                        className="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 cursor-pointer disabled:bg-gray-500"
                        disabled={processing}
                    >
                        {editMode ? "Update" : "Save"}
                    </button>
                </form>
            </div>
        </div>
    );
};
