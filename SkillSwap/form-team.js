document.addEventListener('DOMContentLoaded', () => {
    const addMemberBtn = document.getElementById('addMemberBtn');
    const teamList = document.getElementById('teamList');

    addMemberBtn.addEventListener('click', () => {
        const emailInput = document.getElementById('teamMemberEmail');
        const email = emailInput.value.trim();

        if (email) {
            // Create a new list item with the email and remove button
            const li = document.createElement('li');
            li.innerHTML = `${email} <button class="remove-btn">Remove</button>`;
            teamList.appendChild(li);

            // Clear the input
            emailInput.value = '';
        } else {
            alert('Please enter a valid email.');
        }
    });

    teamList.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-btn')) {
            e.target.parentElement.remove();
        }
    });
});
