import { isValid, differenceInYears, format, parse } from "date-fns";
import { EmployeeDetail, EmployeeFormData } from "../../../types/global";

export const calculateAge = (dob: string | null) => {
    if (!dob) return null;

    const date = new Date(dob);
    if (!isValid(date)) return null;

    return differenceInYears(new Date(), date);
};

export const mapEmployeeToForm = (emp: EmployeeDetail): EmployeeFormData => {
    return {
        employee: {
            id: emp.id,
            actual_emp_id: Number(emp.actual_emp_id),
            code: emp.code,
            name: emp.name,
            gender: emp.gender as "male" | "female" | "other",
            d_o_j: emp.d_o_j?.split("T")[0],
            d_o_l: emp.d_o_l?.split("T")[0] || null,
            status: emp.status,
            audit: emp.audit,
            company_id: Number(emp.company_id),
            dept_id: Number(emp.dept_id),
            cat_id: Number(emp.cat_id),
            des_id: Number(emp.des_id),
            sal_type: emp.sal_type || "BASIC + DA",
            salary: emp.salary ? Number(emp.salary) : null,
            esi_eligible: emp.esi_eligible,
            esi_number: emp.esi_number,
            pf_number: emp.pf_number
        },

        personal: {
            id: emp.personal_detail?.id,
            emp_id: Number(emp.personal_detail?.emp_id),
            img_path: emp.personal_detail?.img_path || null,
            img: null, // ⚠️ file cannot be prefilled
            parent_name: emp.personal_detail?.parent_name || null,
            marital_status: emp.personal_detail?.marital_status || null,
            d_o_b: emp.personal_detail?.d_o_b || null,
            age: emp.personal_detail?.age || null,
            present_address: emp.personal_detail?.present_address || null,
            permanent_address: emp.personal_detail?.permanent_address || null,
            mobile: emp.personal_detail?.mobile || null,
            religion: emp.personal_detail?.religion || null,
            physically_challenged:
                emp.personal_detail?.physically_challenged ?? false,
            if_yes_details: emp.personal_detail?.if_yes_details || null,
        },

        family: emp.family.map((f) => ({
            id: f.id,
            emp_id: Number(f.emp_id),
            name: f.name,
            d_o_b: f.d_o_b,
            age: f.age,
            relationship: f.relationship,
            residing_with: f.residing_with,
            profession: f.profession,
            earnings: f.earnings ? Number(f.earnings) : null,
        })),

        nominees: emp.nominees.map((n) => ({
            id: n.id,
            emp_id: Number(n.emp_id),
            name: n.name,
            relationship: n.relationship || "",
            residing_with: n.residing_with,
            d_o_b: n.d_o_b,
            age: n.age,
            profession: n.profession,
            salary: n.salary ? Number(n.salary) : null,
            address: n.address,
        })),
    };
};

export const formatTime = (time?: string) => {
    if (!time) return "";

    const clean = time.split(".")[0];
    const parsed = parse(clean, "HH:mm:ss", new Date());

    return isValid(parsed) ? format(parsed, "hh:mm a") : "";
};

export const extractHourAndMinutes = (time: string) => {
    if (!time) return "";
    const clean = time.split(":").slice(0, 2).join(":");
    return clean
}
