import { createToast } from "mosha-vue-toastify";
// import the styling for the toast
import "mosha-vue-toastify/dist/style.css";

export default {
    success(title, description = "") {
        createToast(
            {
                title,
                description,
            },
            {
                position: "top-center",
                type: "success",
                transition: "slide",
                showIcon: true,
            }
        );
    },

    danger(title, description = "") {
        createToast(
            {
                title,
                description,
            },
            {
                position: "top-center",
                type: "danger",
                transition: "slide",
                showIcon: true,
            }
        );
    },

    warning(title, description = "") {
        createToast(
            {
                title,
                description,
            },
            {
                position: "top-center",
                type: "warning",
                transition: "slide",
                showIcon: true,
            }
        );
    },

    info(title, description = "") {
        createToast(
            {
                title,
                description,
            },
            {
                position: "top-center",
                type: "info",
                transition: "slide",
                showIcon: true,
            }
        );
    },
};
