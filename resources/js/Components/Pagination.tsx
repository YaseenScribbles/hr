import { router } from "@inertiajs/react";

type Link = {
    url: string | null;
    label: string;
    active: boolean;
};

export default function Pagination({ links }: { links: Link[] }) {
    if (!links || links.length <= 3) return null;

    return (
        <div className="flex flex-wrap items-center justify-center gap-2 mt-6">
            {links.map((link, index) => {
                const isDisabled = !link.url;

                return (
                    <button
                        key={index}
                        disabled={isDisabled}
                        onClick={() => link.url && router.visit(link.url, {
                            preserveScroll: true,
                            preserveState: true
                        })}
                        dangerouslySetInnerHTML={{ __html: link.label }}
                        className={`
                            px-3 py-1 rounded-md text-sm cursor-pointer
                            transition
                            ${link.active
                                ? "bg-blue-600 text-white"
                                : "bg-gray-700 text-gray-200 hover:bg-gray-600"
                            }
                            ${isDisabled ? "opacity-40 cursor-not-allowed" : ""}
                        `}
                    />
                );
            })}
        </div>
    );
}
