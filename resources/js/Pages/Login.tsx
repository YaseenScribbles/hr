import { useForm, } from "@inertiajs/react";
import logo from "../../images/logo.png";

function Login() {
    const { data, setData, post, errors } = useForm({
        email: "",
        password: "",
    });

    return (
        <div className="min-h-screen flex flex-col items-center justify-center bg-gray-950">
            <div className="bg-gray-900/30  p-8 rounded-t-sm shadow-md shadow-gray-500 w-96 border-b border-gray-300">
                <h1 className="text-5xl mb-4 text-center text-white font-logo">
                    H R M
                </h1>
                <h2 className="text-2xl font-bold mb-4 text-center text-white">
                    Login
                </h2>
                <p className="text-gray-400 text-center">
                    Please enter your credentials.
                </p>
                <form
                    className="mt-6"
                    onSubmit={(e) => {
                        e.preventDefault();
                        post("/login");
                    }}
                >
                    <div className="mb-4">
                        <label
                            className="block text-gray-300 mb-2"
                            htmlFor="email"
                        >
                            Email
                        </label>
                        <input
                            type="email"
                            id="email"
                            className="w-full px-3 py-2 border rounded-md text-white focus:outline-none outline-none focus:ring focus:border-cyan-300"
                            placeholder="Enter your email"
                            value={data.email}
                            onChange={(e) => setData("email", e.target.value)}
                        />
                        {errors.email && (
                            <p className="text-red-500 text-sm mt-1">
                                {errors.email}
                            </p>
                        )}
                    </div>
                    <div className="mb-4">
                        <label
                            className="block text-gray-300 mb-2"
                            htmlFor="password"
                        >
                            Password
                        </label>
                        <input
                            type="password"
                            id="password"
                            className="w-full px-3 py-2 border rounded-md text-white focus:outline-none outline-none focus:ring focus:border-cyan-300"
                            placeholder="Enter your password"
                            value={data.password}
                            onChange={(e) =>
                                setData("password", e.target.value)
                            }
                        />
                        {errors.password && (
                            <p className="text-red-500 text-sm mt-1">
                                {errors.password}
                            </p>
                        )}
                    </div>
                    <button
                        type="submit"
                        className="w-full bg-cyan-600 text-white py-2 px-4 rounded-md hover:bg-cyan-700 focus:outline-none outline-none focus:ring focus:border-cyan-300"
                    >
                        Login
                    </button>

                </form>
            </div>
            <div className="flex justify-center bg-gray-900/30 p-4 rounded-b-sm shadow-md shadow-gray-500 w-96">
                <img src={logo} alt="Logo" className="w-60 invert" />
            </div>
        </div>
    );
}

export default Login;
