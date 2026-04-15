// global.d.ts
declare module "@inertiajs/core" {
    // base configuration for the app; keeps track of what the Laravel
    // middleware is sharing.  You can add other globals here as needed.
    export interface InertiaConfig {
        sharedPageProps: {
            auth: { user: { id: number; name: string } | null };
            appName: string;
        };
        flashDataType: {
            toast?: { type: "success" | "error"; message: string };
        };
        errorValueType: string[];
    }

    /**
     * default shape of `page.props` exposed by `usePage()`.
     *
     * Individual pages will augment this via intersection when they
     * declare their own props type — e.g. `type DeptProps = {…} & PageProps`.
     */
    export interface PageProps {
        // repeat the shared props so that `usePage<PageProps>()` works
        auth: {
            user: { id: number; name: string; role: "admin" | "user" } | null;
        };
        appName: string;

        // flash messages coming from session/redirects
        flash: {
            toast?: { type: "success" | "error"; message: string };
            [key: string]: any; // other flash keys are fine
        };

        //companies assigned to user
        user_companies: Company[];

        // allow arbitrary extra props for each page
        [key: string]: any;
    }

}

export type Employee = {
    id?: number;
    actual_emp_id?: number;
    code: string;
    name: string;
    gender: "male" | "female" | "other";
    d_o_j: string;
    d_o_l?: string | null;
    status: boolean;
    audit: boolean;
    company_id: number;
    dept_id: number;
    cat_id: number;
    des_id: number;
    sal_type?: string | null;
    salary?: number | null;
    esi_eligible?: boolean;
    esi_number?: string | null;
    pf_number?: string | null;
};

export type EmployeePersonal = {
    id?: number;
    emp_id?: number;

    img_path?: string | null;
    img?: File | null;
    parent_name?: string | null;
    marital_status?: string | null;
    d_o_b?: string | null;
    age?: number | null;

    present_address?: string | null;
    permanent_address?: string | null;

    mobile?: string | null;
    religion?: string | null;

    physically_challenged: boolean;
    if_yes_details?: string | null;
};

export type EmployeeNominee = {
    id?: number;
    emp_id?: number;
    company_id?: number;

    name: string;
    relationship: string;
    residing_with: boolean;

    d_o_b?: string | null;
    age?: number | null;

    profession?: string | null;
    salary?: number | null;
    address?: string | null;
};

export type EmployeeFamily = {
    id?: number;
    emp_id?: number;
    company_id?: number;

    name?: string | null;
    d_o_b?: string | null;
    age?: number | null;
    relationship?: string | null;

    residing_with: boolean;

    profession?: string | null;
    earnings?: number | null;
};

export type EmployeeFormData = {
    employee: Employee;
    personal: EmployeePersonal;
    nominees: EmployeeNominee[];
    family: EmployeeFamily[];
};

export type EmployeeListItem = {
    id: number;
    code: string;
    name: string;
    gender: string;
    status: string;

    company: {
        id: number;
        name: string;
    };

    department: {
        id: number;
        name: string;
    };

    designation: {
        id: number;
        name: string;
    };

    personal_detail?: {
        img_path?: string | null;
        mobile?: string | null;
    };

    nominees_count: number;
    family_count: number;

    created_at: string;
};

export type PaginatedData<T> = {
    current_page: number;
    data: T[];
    first_page_url: string;
    from: number | null;
    last_page: number;
    last_page_url: string;
    links: {
        url: string | null;
        label: string;
        page: number | null;
        active: boolean;
    }[];
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number | null;
    total: number;
};

export interface EmployeeDetail {
    id: number;
    actual_emp_id: string;
    code: string;
    name: string;
    gender: "male" | "female" | string;
    d_o_j: string;
    d_o_l: string | null;
    status: boolean;
    audit: boolean;
    sal_type: string | null;
    salary: string | null;
    esi_eligible: boolean;
    esi_number: string | null;
    pf_number: string | null;

    company_id: string;
    dept_id: string;
    cat_id: string;
    des_id: string;

    created_at: string;
    updated_at: string;

    company: Company;
    department: Department;
    designation: Designation;
    personal_detail: PersonalDetail;

    family: FamilyMember[];
    nominees: Nominee[];
}

export interface Company {
    id: number;
    name: string;
    address: string;
    email: string;
    phone: string;
    website: string;
    district: string;
    state: string;
    pincode: string;
    gst: string;
    is_active: string;
    created_by: string;
    updated_by: string | null;
    created_at: string;
    updated_at: string;
}

export interface Department {
    id: number;
    company_id: string;
    name: string;
    is_active: string;
    created_by: string;
    updated_by: string | null;
    created_at: string;
    updated_at: string;
}

export interface Designation {
    id: number;
    company_id: string;
    name: string;
    is_active: string;
    created_by: string;
    updated_by: string | null;
    created_at: string;
    updated_at: string;
}

export interface PersonalDetail {
    id: number;
    emp_id: string;
    img_path: string | null;

    parent_name: string | null;
    marital_status: string | null;
    d_o_b: string | null;
    age: number | null;

    present_address: string | null;
    permanent_address: string | null;

    mobile: string | null;
    religion: string | null;

    physically_challenged: boolean;
    if_yes_details: string | null;

    created_at: string;
    updated_at: string;
}


export interface FamilyMember {
    id: number;
    emp_id: string;
    name: string;

    d_o_b: string | null;
    age: number | null;
    relationship: string | null;

    residing_with: boolean;
    profession: string | null;
    earnings: string | null;

    created_at: string;
    updated_at: string;
}

export interface Nominee {
    id: number;
    emp_id: string;
    name: string;
    relationship: string | null;

    residing_with: boolean;

    d_o_b: string | null;
    age: number | null;

    profession: string | null;
    salary: string | null;
    address: string | null;

    created_at: string;
    updated_at: string;
}
