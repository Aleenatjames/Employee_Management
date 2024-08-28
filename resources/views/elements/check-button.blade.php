<div>
    <div class="inline-flex items-center">
        <button id="checkButton" class="inline-flex justify-center items-center text-center px-3 py-1 text-sm font-bold text-white rounded-full mt-2 mr-2"
            style="width: 122px;" onclick="toggleCheckInStatus()">
            Check-in
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-white ml-2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        </button>
        <span id="timer" class="text-md font-light text-white-700 mt-2 ml-2"></span>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let isCheckedIn = false;
    let timerInterval = null;
    let totalSeconds = 0;
    let lastCheckInDate = null;
    const employeeId = '{{ Auth::guard('employee')->user()->id }}'; // Get employee ID dynamically

    // Load state from local storage on page load
    $(document).ready(function() {
        const storedIsCheckedIn = localStorage.getItem(`isCheckedIn_${employeeId}`);
        const storedTotalSeconds = parseInt(localStorage.getItem(`totalSeconds_${employeeId}`)) || 0;
        const storedLastCheckInDate = localStorage.getItem(`lastCheckInDate_${employeeId}`);

        if (storedIsCheckedIn !== null) {
            isCheckedIn = JSON.parse(storedIsCheckedIn);
        }
        totalSeconds = storedTotalSeconds;
        lastCheckInDate = storedLastCheckInDate || new Date().toDateString(); 

        updateButtonState();
        updateTimer(); // Ensure the timer always shows, even on page load

        if (isCheckedIn) {
            startTimer();
        }

        $.ajax({
            url: '/get-check-in-status',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // This could be used to sync state if needed
            },
            error: function(xhr, status, error) {
                console.error('Failed to retrieve check-in status:', status, error);
            }
        });
    });

    function toggleCheckInStatus() {
        isCheckedIn = !isCheckedIn;
        updateButtonState();

        $.ajax({
            url: '/toggle-check-in-status',
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: JSON.stringify({ isCheckedIn: isCheckedIn }),
            success: function(data) {
                if (data.success) {
                    if (isCheckedIn) {
                        startTimer();
                    } else {
                        stopTimer();
                    }
                    // Update local storage
                    localStorage.setItem(`isCheckedIn_${employeeId}`, JSON.stringify(isCheckedIn));
                } else {
                    console.error('Failed to update check-in status');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', status, error);
            }
        });
    }

    function updateButtonState() {
        const button = $('#checkButton');
        const buttonText = isCheckedIn ? 'Check-out' : 'Check-in';

        // Find the text node and update its value
        button.contents().filter(function() {
            return this.nodeType === Node.TEXT_NODE;
        }).first().replaceWith(buttonText + ' ');

        // Update the button background color
        const newColor = isCheckedIn ? '#e95b6d' : '#27cda5';
        button.css('background-color', newColor);
        const newWidth = isCheckedIn ? '130px' : '122px';
        button.css('width', newWidth);
    }

    function startTimer() {
    if (timerInterval) {
        clearInterval(timerInterval);
    }
    timerInterval = setInterval(function() {
        totalSeconds++; // Increment the totalSeconds first
        updateTimer(); // Then update the timer display
    }, 1000);
}

    function stopTimer() {
        if (timerInterval) {
            clearInterval(timerInterval);
        }
        // Save state to local storage
        localStorage.setItem(`totalSeconds_${employeeId}`, totalSeconds);
        localStorage.setItem(`lastCheckInDate_${employeeId}`, lastCheckInDate);
    }

    function updateTimer() {
    const currentDate = new Date().toDateString();

    // Check if the day has changed
    if (lastCheckInDate && lastCheckInDate !== currentDate) {
        totalSeconds = 0; // Reset the timer if the day has changed
        lastCheckInDate = currentDate;
        localStorage.setItem(`lastCheckInDate_${employeeId}`, lastCheckInDate);
    }

    const hours = Math.floor(totalSeconds / 3600);
    const minutes = Math.floor((totalSeconds % 3600) / 60);
    const seconds = totalSeconds % 60;
    $('#timer').text(`${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')} Hrs`);

    // Save the totalSeconds in local storage after updating the timer
    localStorage.setItem(`totalSeconds_${employeeId}`, totalSeconds);
}
</script>
