import Swal from 'sweetalert2';

const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    showCloseButton: true,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    }
});

const progressColors = {
    success: "#22c55e",
    error: "#ef4444",
    warning: "#facc15",
    info: "#3b82f6",
};


export const fireToast = (status: keyof typeof progressColors, message: string) => {
    Toast.fire({
        icon: status,
        title: message,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;

            let bar = toast.querySelector('.swal2-timer-progress-bar');
            if (bar && progressColors[status]) {
                bar.style.backgroundColor = progressColors[status];
            }
        }
    });
}