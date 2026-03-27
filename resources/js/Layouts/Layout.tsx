import { router } from "@inertiajs/react";
import { route } from "ziggy-js";
import { Toaster } from "react-hot-toast";

interface LayoutProps {
    children: React.ReactNode;
    role?: "admin" | "user" | undefined;
}

function Layout({ children, role }: LayoutProps) {

    return (
        <div className="min-h-screen bg-gray-950">
            {/* Navbar */}
            <nav className="bg-gray-900/30 p-4 shadow-md shadow-gray-500">
                <div className="container mx-auto flex items-center justify-between">
                    <h1 className="text-3xl text-white font-logo">H R M</h1>
                    <div className="flex space-x-4">
                        <a
                            href={route("dashboard")}
                            className="text-gray-300 hover:text-white"
                        >
                            Dashboard
                        </a>
                        {/* Show drop down while hovering and add three options */}
                        <div className="relative">
                            <a
                                href="#"
                                className="text-gray-300 hover:text-white"
                                onMouseEnter={(e) => {
                                    const dropdown = e.currentTarget
                                        .nextElementSibling as HTMLElement;
                                    dropdown.classList.remove("hidden");
                                }}
                            >
                                Master
                            </a>
                            {/* Remove hidden class to show dropdown while hovering */}
                            <div
                                className="absolute left-0 mt-2 w-48 bg-gray-800 rounded-md shadow-lg py-2 hidden"
                                onMouseLeave={(e) => {
                                    const dropdown = e.currentTarget;
                                    dropdown.classList.add("hidden");
                                }}
                            >
                                {role && role === "admin" && (
                                    <>
                                        <a
                                            href="#"
                                            onClick={() =>
                                                router.visit(
                                                    route("users.index"),
                                                )
                                            }
                                            className="block px-4 py-2 text-gray-300 hover:bg-gray-700"
                                        >
                                            User
                                        </a>
                                        <a
                                            href="#"
                                            onClick={() =>
                                                router.visit(
                                                    route("companies.index"),
                                                )
                                            }
                                            className="block px-4 py-2 text-gray-300 hover:bg-gray-700"
                                        >
                                            Company
                                        </a>
                                    </>
                                )}
                                <a
                                    href="#"
                                    onClick={() =>
                                        router.visit(route("departments.index"))
                                    }
                                    className="block px-4 py-2 text-gray-300 hover:bg-gray-700"
                                >
                                    Department
                                </a>
                                <a
                                    href="#"
                                    onClick={() =>
                                        router.visit(route("categories.index"))
                                    }
                                    className="block px-4 py-2 text-gray-300 hover:bg-gray-700"
                                >
                                    Category
                                </a>
                                <a
                                    href="#"
                                    onClick={() =>
                                        router.visit(
                                            route("designations.index"),
                                        )
                                    }
                                    className="block px-4 py-2 text-gray-300 hover:bg-gray-700"
                                >
                                    Designation
                                </a>
                            </div>
                        </div>
                        <a
                            href="#"
                            onClick={() =>
                                router.visit(
                                    route("employees.index"),
                                )
                            }
                            className="text-gray-300 hover:text-white">
                            Employee
                        </a>
                        <a href="#" className="text-gray-300 hover:text-white">
                            Setting
                        </a>
                        <a
                            href="#"
                            className="text-gray-300 hover:text-white"
                            onClick={(e) => {
                                e.preventDefault();
                                router.post("/logout");
                            }}
                        >
                            Logout
                        </a>
                    </div>
                </div>
            </nav>
            {/* main content */}
            <main className="container mx-auto p-4">{children}</main>
            {/* Toast Notifications */}
            <Toaster position="bottom-right" />
        </div>
    );
}

export default Layout;
