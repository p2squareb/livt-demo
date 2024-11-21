import { toast, type ToastOptions } from 'vue3-toastify';
import { getTheme } from "@/theme";

export const notify = (type: string, message: string) => {

    toast(message, {
        autoClose: 1300,
        position: toast.POSITION.TOP_RIGHT,
        type: type,
        dangerouslyHTMLString: false,
        toastStyle: {
            fontSize: '0.875rem',
            fontWeight: 'bold',
            lineHeight: '1.25',
            padding: '0.75rem 1.25rem',
            borderRadius: '0.25rem',
            boxShadow: '0 0 0.5rem rgba(0, 0, 0, 0.1)',
            color: getTheme() === 'dark' ? '#fff' : '#333',
            backgroundColor: getTheme() === 'dark' ? '#374152' : '#f7f7f7',
            border: getTheme() === 'dark' ? '1px solid #333' : '1px solid #ccc',
            width: '400px',
        },
        closeButton: false,
    } as ToastOptions);

}

