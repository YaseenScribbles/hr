import { EmployeeFormData } from "../../../../../types/global";
import { calculateAge } from "../../../Helpers/Functions";

type Props = {
    data: EmployeeFormData;
    setData: (key: keyof EmployeeFormData, value: any) => void;
};

export default function PersonalSection({ data, setData }: Props) {

    const updatePersonal = (updates: Partial<EmployeeFormData["personal"]>) => {
        setData("personal", {
            ...data.personal,
            ...updates,
        });
    };

    const handleImageChange = (file: File | null) => {
        updatePersonal({ img: file });
    };

    return (
        <div className="grid grid-cols-1 md:grid-cols-4 gap-6">

            {/* 🖼️ Image Upload */}
            <div className="w-full flex flex-col items-center gap-3">

                {/* Upload Box */}
                <div className="relative w-40 h-40 rounded-xl overflow-hidden border-2 border-dashed border-gray-600 group">

                    {/* Image Preview */}
                    {(data.personal.img || data.personal.img_path) ? (
                        <img
                            src={
                                data.personal.img
                                    ? URL.createObjectURL(data.personal.img)
                                    : `/storage/${data.personal.img_path}`
                            }
                            className="w-full h-full object-cover"
                        />
                    ) : (
                        <label className="w-full h-full flex flex-col items-center justify-center text-gray-400 cursor-pointer hover:text-white">
                            <input
                                type="file"
                                accept="image/*"
                                onChange={(e) =>
                                    handleImageChange(e.target.files?.[0] || null)
                                }
                                className="hidden"
                            />
                            <p className="text-sm">Upload</p>
                            <p className="text-xs text-gray-500">JPG/PNG</p>
                        </label>
                    )}

                    {/* Overlay (only when image exists) */}
                    {(data.personal.img || data.personal.img_path) && (
                        <div className="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 flex flex-col items-center justify-center gap-2 transition">

                            {/* Replace */}
                            <label className="px-3 py-1 bg-blue-600 text-white text-sm rounded cursor-pointer hover:bg-blue-700">
                                Replace
                                <input
                                    type="file"
                                    accept="image/*"
                                    onChange={(e) =>
                                        handleImageChange(e.target.files?.[0] || null)
                                    }
                                    className="hidden"
                                />
                            </label>

                            {/* Remove */}
                            <button
                                type="button"
                                onClick={() => handleImageChange(null)}
                                className="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700"
                            >
                                Remove
                            </button>
                        </div>
                    )}
                </div>

                {/* File Name */}
                {data.personal.img && (
                    <p className="text-xs text-gray-400 truncate max-w-40">
                        {data.personal.img.name}
                    </p>
                )}
            </div>

            {/* 🧾 Fields */}
            <div className="grid grid-cols-1 md:grid-cols-6 md:col-span-3 gap-4">

                {/* Parent Name */}
                <div className="md:col-span-2">
                    <label className="text-gray-300 text-sm">Spouse/Parent Name</label>
                    <input
                        type="text"
                        value={data.personal.parent_name ?? ""}
                        onChange={(e) =>
                            updatePersonal({ parent_name: e.target.value })
                        }
                        className="w-full mt-1 p-2 rounded bg-gray-800 text-white"
                    />
                </div>

                {/* Marital Status */}
                <div className="md:col-span-2">
                    <label className="text-gray-300 text-sm">Marital Status</label>
                    <select
                        value={data.personal.marital_status ?? ""}
                        onChange={(e) =>
                            updatePersonal({ marital_status: e.target.value })
                        }
                        className="w-full mt-1 p-2 rounded bg-gray-800 text-white"
                    >
                        <option value="">Select</option>
                        <option value="single">Single</option>
                        <option value="married">Married</option>
                    </select>
                </div>

                {/* DOB */}
                <div className="md:col-span-2">
                    <label className="text-gray-300 text-sm">Date of Birth</label>
                    <input
                        type="date"
                        value={data.personal.d_o_b ?? ""}
                        onChange={(e) => {
                            const dob = e.target.value;

                            updatePersonal({
                                d_o_b: dob,
                                age: calculateAge(dob),
                            });
                        }
                        }
                        className="w-full mt-1 p-2 rounded bg-gray-800 text-white"
                    />
                </div>

                {/* Age */}
                <div className="md:col-span-2">
                    <label className="text-gray-300 text-sm">Age</label>
                    <input
                        type="number"
                        value={data.personal.age ?? ""}
                        readOnly
                        className="w-full mt-1 p-2 rounded bg-gray-700 text-white"
                    />
                </div>

                {/* Mobile */}
                <div className="md:col-span-2">
                    <label className="text-gray-300 text-sm">Mobile</label>
                    <input
                        type="text"
                        value={data.personal.mobile ?? ""}
                        onChange={(e) =>
                            updatePersonal({ mobile: e.target.value })
                        }
                        className="w-full mt-1 p-2 rounded bg-gray-800 text-white"
                    />
                </div>

                {/* Religion */}
                <div className="md:col-span-2">
                    <label className="text-gray-300 text-sm">Religion</label>
                    <input
                        type="text"
                        value={data.personal.religion ?? ""}
                        onChange={(e) =>
                            updatePersonal({ religion: e.target.value })
                        }
                        className="w-full mt-1 p-2 rounded bg-gray-800 text-white"
                    />
                </div>

                {/* Physically Challenged */}
                <div className="flex items-center gap-2 mt-6 md:col-span-2">
                    <input
                        type="checkbox"
                        checked={data.personal.physically_challenged ?? false}
                        onChange={(e) =>
                            updatePersonal({
                                physically_challenged: e.target.checked,
                                if_yes_details: e.target.checked
                                    ? data.personal.if_yes_details
                                    : "",
                            })
                        }
                    />
                    <label className="text-gray-300">
                        Physically Challenged
                    </label>
                </div>

                {/* Conditional Field */}
                <div className="md:col-span-4">
                    <label className="text-gray-300 text-sm">
                        If Yes, Details
                    </label>
                    <input
                        type="text"
                        value={data.personal.if_yes_details ?? ""}
                        onChange={(e) =>
                            updatePersonal({ if_yes_details: e.target.value })
                        }
                        className="w-full mt-1 p-2 rounded bg-gray-800 text-white disabled:bg-gray-600"
                        disabled={!data.personal.physically_challenged}
                    />
                </div>

                {/* Present Address */}
                <div className="md:col-span-3">
                    <label className="text-gray-300 text-sm">Present Address</label>
                    <textarea
                        value={data.personal.present_address ?? ""}
                        onChange={(e) =>
                            updatePersonal({ present_address: e.target.value })
                        }
                        className="w-full mt-1 p-2 rounded bg-gray-800 text-white"
                    />
                </div>

                {/* Permanent Address */}
                <div className="md:col-span-3">
                    <label className="text-gray-300 text-sm">Permanent Address</label>
                    <textarea
                        value={data.personal.permanent_address ?? ""}
                        onChange={(e) =>
                            updatePersonal({ permanent_address: e.target.value })
                        }
                        className="w-full mt-1 p-2 rounded bg-gray-800 text-white"
                    />
                </div>

            </div>
        </div>
    );
}
