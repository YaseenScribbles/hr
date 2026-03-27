import { useForm } from "@inertiajs/react";
import { useEffect } from "react";

interface AddEditModalProps {
    title: string;
    isOpen: boolean;
    onClose: () => void;
    editMode?: boolean;
    editItem?: {
        id: number;
        company_id: number;
        name: string;
        active: boolean | string;
    };
    postRoute: string;
    companies?: {
        id: number;
        name: string;
    }[];
}

const AddEditModal = ({
    title,
    isOpen,
    onClose,
    editMode = false,
    editItem,
    postRoute,
    companies,
}: AddEditModalProps) => {
    if (!isOpen) return null;

    const { data, setData, post, errors, processing } = useForm({
        company_id: 1,
        name: "",
        active: true,
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        if (editMode && editItem) {
            // Update logic here (e.g., put request)
            // post(`/departments/${editItem.id}`, { ...data, _method: 'put' });
            post(`${postRoute}/${editItem.id}?_method=put`, {
                onSuccess: () => {
                    onClose();
                },
            });
        } else {
            // Create logic here (e.g., post request)
            // post('/departments', data);
            post(postRoute, {
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
                name: editItem.name,
                active: editItem.active == "1" ? true : false,
            });
        } else {
            setData({
                company_id: 1,
                name: "",
                active: true,
            });
        }
    }, [editMode, editItem]);

    return (
        <div className="fixed inset-0 bg-gray-900/50 flex items-center justify-center">
            <div className="bg-gray-800 rounded-md shadow-md shadow-gray-500 p-6 w-96">
                <div className="flex justify-between items-center mb-4">
                    <h2 className="text-xl font-bold text-white">{title}</h2>
                    <button
                        className="text-white text-lg hover:text-gray-300 cursor-pointer"
                        onClick={onClose}
                    >
                        X
                    </button>
                </div>
                <form onSubmit={handleSubmit}>
                    <div className="mb-4">
                        <label
                            className="block text-gray-300 mb-2"
                            htmlFor="company"
                        >
                            Company
                        </label>
                        <select
                            id="company"
                            className="w-full rounded p-2 bg-gray-600 text-white placeholder:text-gray-400 border border-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value={data.company_id}
                            onChange={(e) =>
                                setData("company_id", e.target.value)
                            }
                        >
                            {companies?.map((company) => (
                                <option key={company.id} value={company.id}>
                                    {company.name}
                                </option>
                            ))}
                        </select>
                    </div>
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
                    {editMode && editItem && (
                        <div className="flex items-center mb-4">
                            <input
                                type="checkbox"
                                id="active"
                                className="h-4 w-4 text-blue-600 bg-gray-700 border-gray-500 focus:ring-blue-500 focus:ring-2 mr-2"
                                checked={data.active}
                                onChange={(e) =>
                                    setData("active", e.target.checked)
                                }
                            />
                            <label className="text-gray-300" htmlFor="active">
                                Active
                            </label>
                        </div>
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

export default AddEditModal;
