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
    const employeeId = "{{ Auth::guard('employee')->user()->id }}"; // Get employee ID dynamically

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

       

        // Listen for localStorage changes
        window.addEventListener('storage', function(event) {
            if (event.key === `isCheckedIn_${employeeId}`) {
                isCheckedIn = JSON.parse(event.newValue);
                updateButtonState();
                updateTimer();
                if (isCheckedIn) {
                    startTimer();
                } else {
                    stopTimer();
                }
            } else if (event.key === `totalSeconds_${employeeId}`) {
                totalSeconds = parseInt(event.newValue) || 0;
                updateTimer();
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
                    localStorage.setItem(`totalSeconds_${employeeId}`, totalSeconds);
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
        // Save the start time
        const startTime = Date.now() - (totalSeconds * 1000);
        timerInterval = setInterval(function() {
            totalSeconds = Math.floor((Date.now() - startTime) / 1000);
            updateTimer();
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
<script>
    $(document).ready(function() {
        // Function to calculate the current time position in percentage relative to 9 AM - 6 PM
        function getCurrentTimePosition() {
            const startTime = new Date();
            startTime.setHours(9, 0, 0, 0); // 9 AM
            const endTime = new Date();
            endTime.setHours(18, 0, 0, 0); // 6 PM

            const now = new Date();

            // Ensure the current time is between 9 AM and 6 PM
            if (now < startTime) {
                return 0; // Before 9 AM
            } else if (now > endTime) {
                return 100; // After 6 PM
            }

            const totalMinutes = (endTime - startTime) / 60000; // Total minutes between 9 AM and 6 PM (9 hours = 540 minutes)
            const elapsedMinutes = (now - startTime) / 60000; // Elapsed minutes since 9 AM

            return (elapsedMinutes / totalMinutes) * 100; // Percentage of time elapsed
        }

        function updateCurrentTimeLine() {
            const currentTimePosition = getCurrentTimePosition();
            $('.current-time-line').css('width', currentTimePosition + '%');
        }

        // Call updateCurrentTimeLine every minute to update the green line
        updateCurrentTimeLine();
        setInterval(updateCurrentTimeLine, 60000); // Update every minute
    });
</script>
