   // Sample data for the charts
   const dayData = {
    labels: Array.from({ length: 24 }, (_, i) => i + "h"),
    datasets: [{
        label: 'Intake Calories',
        data: [100, 150, 120, 200, 250, 180, 220, 190, 160, 240, 280, 300, 180, 220, 240, 200, 250, 280, 260, 220, 180, 150, 200, 120],
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1
    }]
};

const weekData = {
    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
    datasets: [{
        label: 'Intake Calories',
        data: [500, 600, 550, 700, 750, 680, 720],
        backgroundColor: 'rgba(255, 99, 132, 0.2)',
        borderColor: 'rgba(255, 99, 132, 1)',
        borderWidth: 1
    }]
};

const monthData = {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    datasets: [{
        label: 'Intake Calories',
        data: [2500, 2800, 2700, 3000, 3200, 2900, 3100, 3200, 3100, 2900, 2800, 2700],
        backgroundColor: 'rgba(54, 162, 235, 0.2)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1
    }]
};

// Create the day chart
const dayChart = new Chart(document.getElementById('dayChart'), {
    type: 'line',
    data: dayData
});

// Create the week chart
const weekChart = new Chart(document.getElementById('weekChart'), {
    type: 'bar',
    data: weekData
});

// Create the month chart
const monthChart = new Chart(document.getElementById('monthChart'), {
    type: 'line',
    data: monthData
});