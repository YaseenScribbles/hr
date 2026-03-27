import { createInertiaApp, router } from "@inertiajs/react";
import { createRoot } from "react-dom/client";
import "../css/app.css";

createInertiaApp({
    resolve: (name: string) => {
        const pages = import.meta.glob("./Pages/**/*.tsx", { eager: true });
        return pages[`./Pages/${name}.tsx`];
    },

    setup({
        el,
        App,
        props,
    }: {
        el: Element;
        App: React.ComponentType<any>;
        props: any;
    }) {
        const root = createRoot(el);
        root.render(<App {...props} />);
    },
});
