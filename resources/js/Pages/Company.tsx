import { router, useForm } from "@inertiajs/react";
import Layout from "../Layouts/Layout";
import React, { useEffect, useState } from "react";
import { route } from "ziggy-js";
import { PageProps } from "@inertiajs/core";
import toast from "react-hot-toast";

interface Props extends PageProps {
    companies: Company[];
}

const Company = ({ companies, flash, auth }: Props) => {
    const [showModal, setShowModal] = useState(false);
    const [editMode, setEditMode] = useState(false);
    const [editItem, setEditItem] = useState<Company | undefined>(undefined);

    useEffect(() => {
        if(flash && flash.toast){
            toast[flash.toast?.type](flash.toast?.message)
        }
    }, [flash])

    return (
        <Layout role={auth.user?.role}>
            <div className="p-4">
                <div className="container flex justify-between items-center">
                    <h1 className="text-2xl text-white font-bold">Company</h1>
                    <button
                        className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                        onClick={() => setShowModal(true)}
                    >
                        Add Company
                    </button>
                </div>
                <div className="container bg-gray-700/30 rounded-md shadow-md shadow-gray-500 p-4 mt-4">
                    <table className="w-full text-left text-white">
                        <thead>
                            <tr className="border-b border-gray-500">
                                <th className="py-2 px-2">Name</th>
                                <th className="py-2 px-2">Address</th>
                                <th className="py-2 px-2">Phone</th>
                                <th className="py-2 px-2">Email</th>
                                <th className="py-2 px-2">Active</th>
                                <th className="py-2 px-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {companies &&
                                companies.map((company) => (
                                    <tr
                                        className="border-b border-gray-500 hover:bg-gray-700/40 transition duration-150"
                                        key={company.id}
                                    >
                                        <td className="py-2 px-2">{company.name}</td>
                                        <td className="py-2 px-2 max-w-72 truncate" title={company.address}>
                                            {company.address}
                                        </td>
                                        <td className="py-2 px-2">
                                            {company.phone}
                                        </td>
                                        <td className="py-2 px-2">
                                            {company.email}
                                        </td>
                                        <td className="py-2 px-2">
                                            {company.is_active === "1"
                                                ? "Yes"
                                                : "No"}
                                        </td>
                                        <td className="py-2 px-2">
                                            <button
                                                className="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded mr-2"
                                                onClick={() => {
                                                    setEditMode(true);
                                                    setEditItem(company);
                                                    setShowModal(true);
                                                }}
                                            >
                                                Edit
                                            </button>
                                            <button
                                                className="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded"
                                                onClick={() =>
                                                    router.delete(
                                                        route(
                                                            "companies.destroy",
                                                            company.id,
                                                        ),
                                                    )
                                                }
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
                    setEditMode(false);
                    setEditItem(undefined);
                    setShowModal(false);
                }}
                editItem={editItem}
            />
        </Layout>
    );
};

export default Company;

type Company = {
    id: number;
    name: string;
    address: string;
    district: string;
    state: string;
    pincode: string;
    email: string;
    phone: string;
    gst: string;
    website: string;
    is_active: string;
};

interface ModalProps {
    isOpen: boolean;
    onClose: () => void;
    editMode: boolean;
    editItem?: Company;
}

const Modal = ({ isOpen, onClose, editMode = false, editItem }: ModalProps) => {
    if (!isOpen) return null;

    const { data, setData, processing, post, errors } = useForm({
        name: "",
        address: "",
        district: "",
        state: "",
        pincode: "",
        email: "",
        phone: "",
        gst: "",
        website: "",
        is_active: true,
    });

    const handleSubmit = (e: React.SubmitEvent) => {
        e.preventDefault();

        if (editMode && editItem) {
            post(`companies/${editItem.id}?_method=put`, {
                onSuccess: () => {
                    onClose();
                },
            });
        } else {
            post(route("companies.store"), {
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
                address: editItem.address,
                district: editItem.district,
                state: editItem.state,
                pincode: editItem.pincode,
                email: editItem.email,
                phone: editItem.phone,
                gst: editItem.gst,
                website: editItem.website,
                is_active: editItem.is_active === "1" ? true : false,
            });
        } else {
            setData({
                name: "",
                address: "",
                district: "",
                state: "",
                pincode: "",
                email: "",
                phone: "",
                gst: "",
                website: "",
                is_active: true,
            });
        }
    }, [editMode, editItem]);

    return (
        <div className="fixed inset-0 bg-gray-900/50 flex items-center justify-center">
            <div className="bg-gray-800 rounded-md shadow-md shadow-gray-500 p-6 min-w-9/12">
                <div className="flex justify-between items-center mb-4">
                    <h2 className="text-xl font-bold text-white">
                        Add Company
                    </h2>
                    <button
                        className="text-white text-lg hover:text-gray-300 cursor-pointer"
                        onClick={onClose}
                    >
                        X
                    </button>
                </div>
                <form onSubmit={handleSubmit}>
                    <div className="grid grid-cols-1 md:grid-cols-6 gap-4">
                        <div className="col-span-2">
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
                                placeholder="Enter company name"
                                value={data.name}
                                onChange={(e) =>
                                    setData("name", e.target.value)
                                }
                            />
                            {errors.name && (
                                <p className="text-red-500 text-sm mt-1">
                                    {errors.name}
                                </p>
                            )}
                        </div>
                        <div className="col-span-4">
                            <label
                                className="block text-gray-300 mb-2"
                                htmlFor="address"
                            >
                                Address
                            </label>
                            <input
                                type="text"
                                id="address"
                                className="w-full rounded p-2 bg-gray-600 text-white placeholder:text-gray-400 border border-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Enter company address"
                                value={data.address}
                                onChange={(e) =>
                                    setData("address", e.target.value)
                                }
                            />
                            {errors.address && (
                                <p className="text-red-500 text-sm mt-1">
                                    {errors.address}
                                </p>
                            )}
                        </div>
                        <div className="col-span-2">
                            <label
                                className="block text-gray-300 mb-2"
                                htmlFor="district"
                            >
                                District
                            </label>
                            <input
                                type="text"
                                id="district"
                                className="w-full rounded p-2 bg-gray-600 text-white placeholder:text-gray-400 border border-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Enter district"
                                value={data.district}
                                onChange={(e) =>
                                    setData("district", e.target.value)
                                }
                            />
                            {errors.district && (
                                <p className="text-red-500 text-sm mt-1">
                                    {errors.district}
                                </p>
                            )}
                        </div>
                        <div className="col-span-2">
                            <label
                                className="block text-gray-300 mb-2"
                                htmlFor="state"
                            >
                                State
                            </label>
                            <input
                                type="text"
                                id="state"
                                className="w-full rounded p-2 bg-gray-600 text-white placeholder:text-gray-400 border border-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Enter state"
                                value={data.state}
                                onChange={(e) =>
                                    setData("state", e.target.value)
                                }
                            />
                            {errors.state && (
                                <p className="text-red-500 text-sm mt-1">
                                    {errors.state}
                                </p>
                            )}
                        </div>
                        <div className="col-span-2">
                            <label
                                className="block text-gray-300 mb-2"
                                htmlFor="pincode"
                            >
                                Pincode
                            </label>
                            <input
                                type="text"
                                id="pincode"
                                className="w-full rounded p-2 bg-gray-600 text-white placeholder:text-gray-400 border border-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Enter pincode"
                                value={data.pincode}
                                onChange={(e) =>
                                    setData("pincode", e.target.value)
                                }
                            />
                            {errors.pincode && (
                                <p className="text-red-500 text-sm mt-1">
                                    {errors.pincode}
                                </p>
                            )}
                        </div>
                        <div className="col-span-2">
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
                                onChange={(e) =>
                                    setData("email", e.target.value)
                                }
                            />
                            {errors.email && (
                                <p className="text-red-500 text-sm mt-1">
                                    {errors.email}
                                </p>
                            )}
                        </div>
                        <div className="col-span-2">
                            <label
                                className="block text-gray-300 mb-2"
                                htmlFor="phone"
                            >
                                Phone
                            </label>
                            <input
                                type="text"
                                id="phone"
                                className="w-full rounded p-2 bg-gray-600 text-white placeholder:text-gray-400 border border-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Enter phone"
                                value={data.phone}
                                onChange={(e) =>
                                    setData("phone", e.target.value)
                                }
                            />
                            {errors.phone && (
                                <p className="text-red-500 text-sm mt-1">
                                    {errors.phone}
                                </p>
                            )}
                        </div>
                        <div className="col-span-2">
                            <label
                                className="block text-gray-300 mb-2"
                                htmlFor="gst"
                            >
                                Gst
                            </label>
                            <input
                                type="text"
                                id="gst"
                                className="w-full rounded p-2 bg-gray-600 text-white placeholder:text-gray-400 border border-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Enter gst"
                                value={data.gst}
                                onChange={(e) => setData("gst", e.target.value)}
                            />
                            {errors.gst && (
                                <p className="text-red-500 text-sm mt-1">
                                    {errors.gst}
                                </p>
                            )}
                        </div>
                        <div className="col-span-2">
                            <label
                                className="block text-gray-300 mb-2"
                                htmlFor="website"
                            >
                                Website
                            </label>
                            <input
                                type="text"
                                id="website"
                                className="w-full rounded p-2 bg-gray-600 text-white placeholder:text-gray-400 border border-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Enter website"
                                value={data.website}
                                onChange={(e) =>
                                    setData("website", e.target.value)
                                }
                            />
                            {errors.website && (
                                <p className="text-red-500 text-sm mt-1">
                                    {errors.website}
                                </p>
                            )}
                        </div>
                        {editMode && (
                            <div className="col-span-1 flex items-end">
                                <div className="flex items-center">
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
                            </div>
                        )}
                        <div className="col-span-2 flex items-end">
                            <button
                                type="submit"
                                className="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 cursor-pointer disabled:bg-gray-500"
                                disabled={processing}
                            >
                                {editMode ? "Update" : "Save"}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    );
};
