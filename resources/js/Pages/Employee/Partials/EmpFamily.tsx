import { EmployeeFormData } from "../../../../../types/global"
import { calculateAge } from "../../../Helpers/Functions";

type Props = {
    data: EmployeeFormData;
    setData: (key: keyof EmployeeFormData, value: any) => void;
};

export default function FamilyTable({ data, setData }: Props) {

    const updateRow = (index: number, field: string, value: any) => {
        const updated = [...data.family];
        updated[index] = {
            ...updated[index],
            [field]: value,
        };
        setData("family", updated);
    };

    const addRow = () => {
        setData("family", [
            ...data.family,
            {
                name: "",
                relationship: "",
                d_o_b: "",
                age: null,
                residing_with: true,
                profession: "",
                earnings: null,
            },
        ]);
    };

    const removeRow = (index: number) => {
        setData(
            "family",
            data.family.filter((_, i) => i !== index)
        );
    };

    return (
        <div className="space-y-3">

            {/* Add Button */}
            <div className="flex justify-between items-center">
                <h3 className="text-white font-semibold">Family Members</h3>
                <button
                    type="button"
                    onClick={addRow}
                    className="px-3 py-1 bg-blue-600 text-white rounded"
                >
                    + Add
                </button>
            </div>

            {/* Table */}
            <div className="overflow-auto border border-gray-700 rounded-lg">
                <table className="w-full text-sm text-white">
                    <thead className="bg-gray-800">
                        <tr>
                            <th className="p-2 text-left">Name</th>
                            <th className="p-2">Relation</th>
                            <th className="p-2">DOB</th>
                            <th className="p-2">Age</th>
                            <th className="p-2">With</th>
                            <th className="p-2">Profession</th>
                            <th className="p-2">Earnings</th>
                            <th className="p-2">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        {data.family.length === 0 && (
                            <tr>
                                <td colSpan={8} className="text-center p-4 text-gray-400">
                                    No family members added
                                </td>
                            </tr>
                        )}

                        {data.family.map((row, index) => (
                            <tr key={index} className="border-t border-gray-700">

                                {/* Name */}
                                <td className="p-2">
                                    <input
                                        value={row.name ?? ""}
                                        onChange={(e) =>
                                            updateRow(index, "name", e.target.value)
                                        }
                                        className="w-full bg-gray-800 p-1 rounded"
                                    />
                                </td>

                                {/* Relationship */}
                                <td className="p-2">
                                    <input
                                        value={row.relationship ?? ""}
                                        onChange={(e) =>
                                            updateRow(index, "relationship", e.target.value)
                                        }
                                        className="w-full bg-gray-800 p-1 rounded"
                                    />
                                </td>

                                {/* DOB */}
                                <td className="p-2">
                                    <input
                                        type="date"
                                        value={row.d_o_b ?? ""}
                                        onChange={(e) => {
                                            const dob = e.target.value;

                                            const updated = [...data.family];
                                            updated[index] = {
                                                ...updated[index],
                                                d_o_b: dob,
                                                age: calculateAge(dob),
                                            };

                                            setData("family", updated);
                                        }}
                                        className="w-full bg-gray-800 p-1 rounded"
                                    />
                                </td>

                                {/* Age */}
                                <td className="p-2">
                                    <input
                                        value={row.age ?? ""}
                                        readOnly
                                        className="w-full bg-gray-700 p-1 rounded text-gray-400"
                                    />
                                </td>

                                {/* Residing */}
                                <td className="p-2 text-center">
                                    <input
                                        type="checkbox"
                                        checked={row.residing_with ?? true}
                                        onChange={(e) =>
                                            updateRow(index, "residing_with", e.target.checked)
                                        }
                                    />
                                </td>

                                {/* Profession */}
                                <td className="p-2">
                                    <input
                                        value={row.profession ?? ""}
                                        onChange={(e) =>
                                            updateRow(index, "profession", e.target.value)
                                        }
                                        className="w-full bg-gray-800 p-1 rounded"
                                    />
                                </td>

                                {/* Earnings */}
                                <td className="p-2">
                                    <input
                                        type="number"
                                        value={row.earnings ?? ""}
                                        onChange={(e) =>
                                            updateRow(index, "earnings", Number(e.target.value))
                                        }
                                        className="w-full bg-gray-800 p-1 rounded"
                                    />
                                </td>

                                {/* Remove */}
                                <td className="p-2 text-center">
                                    <button
                                        type="button"
                                        onClick={() => removeRow(index)}
                                        className="text-red-400 hover:text-red-600"
                                    >
                                        ✕
                                    </button>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </div>
    );
}
