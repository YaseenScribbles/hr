import { PageProps } from "@inertiajs/core";
import Layout from "../Layouts/Layout";
import { useEffect } from "react";
import toast from "react-hot-toast";

interface Props extends PageProps {
}

function Dashboard({ flash, auth }: Props) {
    useEffect(() => {
        if (flash && flash.toast) {
            toast[flash.toast.type](flash.toast.message);
        }
    }, [flash]);

    return (
        <Layout role={auth.user?.role} userName={auth.user?.name}>
            <div>Dashboard</div>
        </Layout>
    );
}

export default Dashboard;
