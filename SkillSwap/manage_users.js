document.addEventListener('DOMContentLoaded', () => {
    const userFormModal = document.getElementById('userFormModal');
    const closeModal = document.getElementById('closeModal');
    const addUserBtn = document.getElementById('addUserBtn');
    const userForm = document.getElementById('userForm');
    const userIdField = document.getElementById('userId');
    const formTitle = document.getElementById('formTitle');

    let users = [];

    // Fetch users on page load
    fetchUsers();

    // Add User Button click event
    addUserBtn.addEventListener('click', () => {
        userForm.reset();
        userIdField.value = '';
        formTitle.textContent = 'Add User';
        userFormModal.style.display = 'block';
    });

    // Close Modal event
    closeModal.onclick = () => {
        userFormModal.style.display = 'none';
    };

    // Form submit event
    userForm.onsubmit = (event) => {
        event.preventDefault();
        const formData = new FormData(userForm);
        const userId = userIdField.value;

        if (userId) {
            // Update user
            fetch(`api/update_user.php`, {
                method: 'POST',
                body: formData,
            }).then(response => response.json()).then(data => {
                fetchUsers();
                userFormModal.style.display = 'none';
            });
        } else {
            // Add new user
            fetch(`api/add_user.php`, {
                method: 'POST',
                body: formData,
            }).then(response => response.json()).then(data => {
                fetchUsers();
                userFormModal.style.display = 'none';
            });
        }
    };

    // Fetch users from the database
    function fetchUsers() {
        fetch('api/get_users.php')
            .then(response => response.json())
            .then(data => {
                users = data;
                renderUsers();
            });
    }

    // Render users in the table
    function renderUsers() {
        const userTableBody = document.getElementById('userTableBody');
        userTableBody.innerHTML = '';
        users.forEach(user => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${user.user_id}</td>
                <td>${user.first_name}</td>
                <td>${user.last_name}</td>
                <td>${user.email}</td>
                <td>${user.role}</td>
                <td>
                    <button onclick="editUser(${user.user_id})">Edit</button>
                    <button onclick="deleteUser(${user.user_id})">Delete</button>
                </td>
            `;
            userTableBody.appendChild(row);
        });
    }

    // Edit user function
    window.editUser = (userId) => {
        const user = users.find(u => u.user_id === userId);
        if (user) {
            userIdField.value = user.user_id;
            document.getElementById('firstName').value = user.first_name;
            document.getElementById('lastName').value = user.last_name;
            document.getElementById('email').value = user.email;
            document.getElementById('role').value = user.role;
            formTitle.textContent = 'Edit User';
            userFormModal.style.display = 'block';
        }
    };

    // Delete user function
    window.deleteUser = (userId) => {
        fetch(`api/delete_user.php`, {
            method: 'POST',
            body: JSON.stringify({ user_id: userId }),
            headers: {
                'Content-Type': 'application/json',
            },
        }).then(response => response.json()).then(data => {
            fetchUsers();
        });
    };
});
