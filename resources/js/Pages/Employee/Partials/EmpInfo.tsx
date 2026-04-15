import { EmployeeFormData } from "../../../../../types/global";

type Props = {
    data: EmployeeFormData;
    setData: (key: keyof EmployeeFormData, value: any) => void;
    companies: Option[];
    departments: Option[];
    categories: Option[];
    designations: Option[];
};

type Option = {
    id: number;
    name: string;
    company_id?: number;
}

export default function EmployeeSection({ data, setData, companies, departments, categories, designations }: Props) {

    const updateEmployee = (field: keyof EmployeeFormData["employee"], value: any) => {
        setData("employee", {
            ...data.employee,
            [field]: value,
        });
    };

    return (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

            {/* Actual Employee Id */}
            <div>
                <label className="text-gray-300 text-sm">Emp Id</label>
                <input
                    type="text"
                    value={data.employee.actual_emp_id}
                    onChange={(e) => updateEmployee("actual_emp_id", e.target.value)}
                    className="w-full mt-1 p-2 rounded bg-gray-800 text-white"
                />
            </div>

            {/* Code */}
            <div>
                <label className="text-gray-300 text-sm">Code</label>
                <input
                    type="text"
                    value={data.employee.code}
                    onChange={(e) => updateEmployee("code", e.target.value)}
                    className="w-full mt-1 p-2 rounded bg-gray-800 text-white"
                />
            </div>

            {/* Name */}
            <div>
                <label className="text-gray-300 text-sm">Name</label>
                <input
                    type="text"
                    value={data.employee.name}
                    onChange={(e) => updateEmployee("name", e.target.value)}
                    className="w-full mt-1 p-2 rounded bg-gray-800 text-white"
                />
            </div>

            {/* Gender */}
            <div>
                <label className="text-gray-300 text-sm">Gender</label>
                <select
                    value={data.employee.gender}
                    onChange={(e) => updateEmployee("gender", e.target.value)}
                    className="w-full mt-1 p-2 rounded bg-gray-800 text-white"
                >
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>

            {/* DOJ */}
            <div>
                <label className="text-gray-300 text-sm">Date of Joining</label>
                <input
                    type="date"
                    value={data.employee.d_o_j}
                    onChange={(e) => updateEmployee("d_o_j", e.target.value)}
                    className="w-full mt-1 p-2 rounded bg-gray-800 text-white"
                />
            </div>

            {/* Company */}
            <div>
                <label className="text-gray-300 text-sm">Company</label>
                {/* <input
                    type="number"
                    value={data.employee.company_id}
                    onChange={(e) => updateEmployee("company_id", Number(e.target.value))}
                    className="w-full mt-1 p-2 rounded bg-gray-800 text-white"
                /> */}
                <select
                    className="w-full mt-1 p-2 rounded bg-gray-800 text-white"
                    value={data.employee.company_id || ""}
                    onChange={(e) => {
                        const companyId = Number(e.target.value);

                        setData("employee", {
                            ...data.employee,
                            company_id: companyId,
                            dept_id: 0,
                            cat_id: 0,
                            des_id: 0,
                        });
                    }}
                >
                    <option value="">Select Company</option>
                    {
                        companies && companies.map(comp => <option key={comp.id} value={comp.id}>
                            {comp.name}
                        </option>)
                    }
                </select>
            </div>

            {/* Department */}
            <div>
                <label className="text-gray-300 text-sm">Department</label>
                {/* <input
                    type="number"
                    value={data.employee.dept_id}
                    onChange={(e) => updateEmployee("dept_id", Number(e.target.value))}
                    className="w-full mt-1 p-2 rounded bg-gray-800 text-white"
                /> */}
                <select
                    className="w-full mt-1 p-2 rounded bg-gray-800 text-white disabled:bg-gray-600"
                    value={data.employee.dept_id || ""}
                    onChange={(e) => updateEmployee("dept_id", Number(e.target.value))}
                    disabled={!data.employee.company_id}
                >
                    <option value="">Select Department</option>
                    {
                        departments && departments.filter(dept => dept.company_id == data.employee.company_id).map(dept => <option key={dept.id} value={dept.id}>
                            {dept.name}
                        </option>)
                    }
                </select>
            </div>

            {/* Category */}
            <div>
                <label className="text-gray-300 text-sm">Category</label>
                {/* <input
                    type="number"
                    value={data.employee.cat_id}
                    onChange={(e) => updateEmployee("cat_id", Number(e.target.value))}
                    className="w-full mt-1 p-2 rounded bg-gray-800 text-white"
                /> */}
                <select
                    className="w-full mt-1 p-2 rounded bg-gray-800 text-white disabled:bg-gray-600"
                    value={data.employee.cat_id || ""}
                    onChange={(e) => updateEmployee("cat_id", Number(e.target.value))}
                    disabled={!data.employee.company_id}
                >
                    <option value="">Select Category</option>
                    {
                        categories && categories.filter(cat => cat.company_id == data.employee.company_id).map(cat => <option key={cat.id} value={cat.id}>
                            {cat.name}
                        </option>)
                    }
                </select>
            </div>

            {/* Designation */}
            <div>
                <label className="text-gray-300 text-sm">Designation</label>
                {/* <input
                    type="number"
                    value={data.employee.des_id}
                    onChange={(e) => updateEmployee("des_id", Number(e.target.value))}
                    className="w-full mt-1 p-2 rounded bg-gray-800 text-white"
                /> */}
                <select
                    className="w-full mt-1 p-2 rounded bg-gray-800 text-white disabled:bg-gray-600"
                    value={data.employee.des_id || ""}
                    onChange={(e) => updateEmployee("des_id", Number(e.target.value))}
                    disabled={!data.employee.company_id}
                >
                    <option value="">Select Designation</option>
                    {
                        designations && designations.filter(des => des.company_id == data.employee.company_id).map(des => <option key={des.id} value={des.id}>
                            {des.name}
                        </option>)
                    }
                </select>
            </div>

            {/* Salary Type */}
            <div>
                <label className="text-gray-300 text-sm">Salary Type</label>
                <select
                    className="w-full mt-1 p-2 rounded bg-gray-800 text-white disabled:bg-gray-600"
                    value={data.employee.sal_type || ""}
                    onChange={(e) => updateEmployee("sal_type", e.target.value)}
                >
                    <option value="BASIC + DA">BASIC + DA</option>
                    <option value="BASIC">BASIC</option>
                </select>
            </div>

            {/* Salary */}
            <div>
                <label className="text-gray-300 text-sm">Salary</label>
                <input
                    type="number"
                    value={data.employee.salary || ""}
                    onChange={(e) => updateEmployee("salary", Number(e.target.value))}
                    className="w-full mt-1 p-2 rounded bg-gray-800 text-white"
                />
            </div>

            <div className="flex flex-col-reverse">

                {/* Status */}
                <div className="flex items-center gap-2">
                    <input
                        type="checkbox"
                        checked={data.employee.status}
                        onChange={(e) => updateEmployee("status", e.target.checked)}
                    />
                    <label className="text-gray-300">Active</label>
                </div>

                {/* Audit */}
                <div className="flex items-center gap-2 mt-6">
                    <input
                        type="checkbox"
                        checked={data.employee.audit}
                        onChange={(e) => updateEmployee("audit", e.target.checked)}
                    />
                    <label className="text-gray-300">Audit</label>
                </div>

            </div>

            {/* ESI Eligible */}
            <div className="flex items-center gap-2">
                <input
                    type="checkbox"
                    checked={data.employee.esi_eligible}
                    onChange={(e) => updateEmployee("esi_eligible", e.target.checked)}
                />
                <label className="text-gray-300">ESI Eligible</label>
            </div>

            {/* ESI Number */}
            <div>
                <label className="text-gray-300 text-sm">ESI Number</label>
                <input
                    type="text"
                    value={data.employee.esi_number || ""}
                    onChange={(e) => updateEmployee("esi_number", e.target.value)}
                    className="w-full mt-1 p-2 rounded bg-gray-800 text-white"
                />
            </div>

            {/* PF Number */}
            <div>
                <label className="text-gray-300 text-sm">PF Number</label>
                <input
                    type="text"
                    value={data.employee.pf_number || ""}
                    onChange={(e) => updateEmployee("pf_number", e.target.value)}
                    className="w-full mt-1 p-2 rounded bg-gray-800 text-white"
                />
            </div>


        </div>
    );
}
