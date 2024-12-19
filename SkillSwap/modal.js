// Get modal and elements
const profileModal = document.getElementById('profileModal');
const profileModalBtn = document.getElementById('profileModalBtn'); // This is now correct
const closeModal = document.querySelector('.close');
const editProfileBtn = document.getElementById('editProfileBtn');
const editProfileForm = document.getElementById('editProfileForm');
const profileInfo = document.querySelector('.profile-info');

// Show Modal
profileModalBtn.addEventListener('click', function() {
    profileModal.style.display = 'block';
});

// Close Modal
closeModal.addEventListener('click', function() {
    profileModal.style.display = 'none';
});

// Toggle Edit Profile Form
editProfileBtn.addEventListener('click', function() {
    profileInfo.style.display = 'none';
    editProfileForm.style.display = 'block';
});

// Close modal when clicking outside the modal
window.addEventListener('click', function(event) {
    if (event.target === profileModal) {
        profileModal.style.display = 'none';
    }
});
profileModal.style.right = '0'; // When opening modal
profileModal.style.right = '-350px'; // When closing modal
