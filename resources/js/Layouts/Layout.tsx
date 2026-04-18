import { router } from "@inertiajs/react";
import { route } from "ziggy-js";
import { Toaster } from "react-hot-toast";
import { useEffect, useRef } from "react";

interface LayoutProps {
    children: React.ReactNode;
    role?: "admin" | "user" | undefined;
    userName?: string;
}

const IDLE_TIMEOUT = 30 * 60 * 1000;

function Layout({ children, role, userName }: LayoutProps) {

    const idleTimer = useRef<number | null>(null);

    const resetIdleTimer = () => {
        if (idleTimer.current) {
            window.clearTimeout(idleTimer.current);
        }
        idleTimer.current = window.setTimeout(() => {
            router.post(route("logout"));
        }, IDLE_TIMEOUT);
    };

    useEffect(() => {
        const events = ["mousemove", "mousedown", "keydown", "touchstart", "scroll"];

        events.forEach((event) => {
            document.addEventListener(event, resetIdleTimer);
        });

        resetIdleTimer();

        return () => {
            if (idleTimer.current) {
                window.clearTimeout(idleTimer.current);
            }
            events.forEach((event) => {
                document.removeEventListener(event, resetIdleTimer);
            });
        };
    }, []);


    return (
        <div className="min-h-screen bg-gray-950">
            {/* Navbar */}
            <nav className="bg-gray-900/30 p-4 shadow-md shadow-gray-500">
                <div className="container mx-auto flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div className="flex items-center gap-4">
                        <h1 className="text-3xl text-white font-logo">H R M</h1>
                    </div>
                    <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-end">

                        <div className="flex flex-wrap items-center gap-4">
                            {/* <a
                            href="#"
                            onClick={() =>
                                router.visit(route("dashboard"))
                            }
                            className="text-gray-300 hover:text-white"
                        >
                            Dashboard
                        </a> */}
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
                                    {
                                        role && role == "admin" &&
                                        <a
                                            href="#"
                                            onClick={() =>
                                                router.visit(
                                                    route("shifts.index"),
                                                )
                                            }
                                            className="block px-4 py-2 text-gray-300 hover:bg-gray-700"
                                        >
                                            Shift Master
                                        </a>
                                    }
                                    <a
                                        href="#"
                                        onClick={() =>
                                            router.visit(
                                                route("defaults.index"),
                                            )
                                        }
                                        className="block px-4 py-2 text-gray-300 hover:bg-gray-700"
                                    >
                                        Defaults
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
                                className="text-gray-300 hover:text-white"
                            >
                                Employee
                            </a>
                            <a
                                href="#"
                                onClick={() => router.visit(route("rosters.index"))}
                                className="text-gray-300 hover:text-white"
                            >
                                Roster
                            </a>
                            <a
                                href="#"
                                onClick={() => router.visit(route("attendance.index"))}
                                className="text-gray-300 hover:text-white"
                            >
                                Attendance
                            </a>
                            <a
                                href="#"
                                onClick={() =>
                                    router.visit(
                                        route("deductions.index"),
                                    )
                                }
                                className="text-gray-300 hover:text-white"
                            >
                                Deductions
                            </a>
                            <a
                                href="#"
                                onClick={() => router.visit(route("reports.index"))}
                                className="text-gray-300 hover:text-white"
                            >
                                Reports
                            </a>
                            {userName && (
                                <div className="group relative hidden sm:flex flex-col items-end rounded-full border border-gray-700 bg-gray-800 px-4 py-2 text-right transition duration-500 hover:bg-gray-700">
                                    <button
                                        type="button"
                                        className="text-sm font-medium text-white focus:outline-none"
                                    >
                                        Hi, {userName}
                                    </button>
                                    <div className="absolute right-0 top-full pt-2 z-10 hidden min-w-35 overflow-hidden rounded-lg shadow-lg shadow-black/40 group-hover:block transition duration-500">
                                        <button
                                            type="button"
                                            className="w-full rounded-md bg-red-600 px-3 py-2 text-sm font-medium text-white hover:bg-red-500"
                                            onClick={(e) => {
                                                e.preventDefault();
                                                router.post(route("logout"));
                                            }}
                                        >
                                            Logout
                                        </button>
                                    </div>
                                </div>
                            )}
                        </div>
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
