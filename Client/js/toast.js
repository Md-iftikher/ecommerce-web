// Enhanced Toast Notification System
const toastQueue = [];
let isToastActive = false;

function showToast(message, type = 'info', action = null) {
    // Add toast to queue
    toastQueue.push({ message, type, action });
    
    // If no toast is currently showing, display the next one
    if (!isToastActive) {
        processToastQueue();
    }
}

function processToastQueue() {
    if (toastQueue.length === 0) {
        isToastActive = false;
        return;
    }

    isToastActive = true;
    const { message, type, action } = toastQueue.shift();
    
    // Create toast container if it doesn't exist
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'fixed top-4 left-0 right-0 flex justify-center z-50 pointer-events-none';
        document.body.appendChild(toastContainer);
    }

    // Create toast element
    const toast = document.createElement('div');
    toast.className = `px-6 py-3 rounded-md shadow-lg text-white flex items-center transform transition-all duration-300 ${
        type === 'error' ? 'bg-red-500' : 
        type === 'success' ? 'bg-green-500' : 
        'bg-blue-500'
    } animate-toast-enter`;
    
    toast.innerHTML = `
        <span>${message}</span>
        ${action ? `<a href="${action.url}" class="ml-3 font-bold underline hover:opacity-80">${action.text}</a>` : ''}
        <button class="ml-4 text-white hover:text-gray-200 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
    `;

    // Add close button functionality
    const closeBtn = toast.querySelector('button');
    closeBtn.addEventListener('click', () => {
        dismissToast(toast);
    });

    // Add to container
    toastContainer.appendChild(toast);

    // Auto-dismiss after delay
    const autoDismissTimer = setTimeout(() => {
        dismissToast(toast);
    }, 3000);

    // Handle manual dismiss
    toast._autoDismissTimer = autoDismissTimer;
}

function dismissToast(toast) {
    clearTimeout(toast._autoDismissTimer);
    toast.classList.remove('animate-toast-enter');
    toast.classList.add('animate-toast-exit');
    
    setTimeout(() => {
        toast.remove();
        
        // Check if container is empty and remove it
        const toastContainer = document.getElementById('toast-container');
        if (toastContainer && toastContainer.children.length === 0) {
            toastContainer.remove();
        }
        
        // Process next toast in queue
        processToastQueue();
    }, 300);
}

// Add CSS animations (add this to your stylesheet)
const style = document.createElement('style');
style.textContent = `
    @keyframes toast-enter {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes toast-exit {
        from { opacity: 1; transform: translateY(0); }
        to { opacity: 0; transform: translateY(-20px); }
    }
    .animate-toast-enter {
        animation: toast-enter 0.3s ease-out forwards;
    }
    .animate-toast-exit {
        animation: toast-exit 0.3s ease-in forwards;
    }
`;
document.head.appendChild(style);