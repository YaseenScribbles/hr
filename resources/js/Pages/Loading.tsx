import { useEffect } from "react"

function Loading() {

  useEffect(() => {
    const timer = setTimeout(() => {
      window.location.href = '/login';
    }, 2000); // Redirect after 2 seconds

    return () => clearTimeout(timer); // Cleanup the timer on component unmount
  }, []);

  return (
    <div className="min-h-screen flex items-center justify-center bg-gray-950">
      <div className="text-white text-2xl animate-pulse">LOADING
        <span className="ml-1 animate-ping">.</span>
        <span className="ml-1 animate-ping">.</span>
        <span className="ml-1 animate-ping">.</span>
        </div>
    </div>
  )
}

export default Loading
