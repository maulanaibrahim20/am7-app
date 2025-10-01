import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";
import fullReload from "vite-plugin-full-reload";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        fullReload(["resources/views/**/*.blade.php"]),
        tailwindcss(),
    ],
});
