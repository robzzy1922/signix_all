import defaultTheme from "tailwindcss/defaultTheme";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            keyframes: {
                fadeIn: {
                    "0%": { opacity: "0" },
                    "100%": { opacity: "1" },
                },
                slideInFromLeft: {
                    "0%": { transform: "translateX(-100%)", opacity: "0" },
                    "100%": { transform: "translateX(0)", opacity: "1" },
                },
                slideInFromRight: {
                    "0%": { transform: "translateX(100%)", opacity: "0" },
                    "100%": { transform: "translateX(0)", opacity: "1" },
                },
                slideInFromTop: {
                    "0%": { transform: "translateY(-100%)", opacity: "0" },
                    "100%": { transform: "translateY(0)", opacity: "1" },
                },
                slideInFromBottom: {
                    "0%": { transform: "translateY(100%)", opacity: "0" },
                    "100%": { transform: "translateY(0)", opacity: "1" },
                },
            },
            animation: {
                fadeIn: "fadeIn 0.5s ease-in",
                slideInFromLeft: "slideInFromLeft 0.5s ease-out",
                slideInFromRight: "slideInFromRight 0.5s ease-out",
                slideInFromTop: "slideInFromTop 0.5s ease-out",
                slideInFromBottom: "slideInFromBottom 0.5s ease-out",
            },
        },
    },
    plugins: [],
};
