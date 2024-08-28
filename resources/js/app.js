import './bootstrap';

function showContent(type) {
    // Hide both contents initially
    document.getElementById('days-content').classList.add('hidden');
    document.getElementById('hours-content').classList.add('hidden');

    // Remove active styles from both buttons
    document.getElementById('days-tab').classList.remove('text-blue-500');
    document.getElementById('hours-tab').classList.remove('text-blue-500');

    // Show the selected content and add active styles to the clicked tab
    if (type === 'days') {
        document.getElementById('days-content').classList.remove('hidden');
        document.getElementById('days-tab').classList.add('text-blue-500');
    } else if (type === 'hours') {
        document.getElementById('hours-content').classList.remove('hidden');
        document.getElementById('hours-tab').classList.add('text-blue-500');
    }
}

// Set the initial view to 'Days'
showContent('days');