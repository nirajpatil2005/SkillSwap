document.addEventListener('DOMContentLoaded', () => {
    // Attach event listeners to all skill buttons
    document.querySelectorAll('.skill-btn').forEach(button => {
        button.addEventListener('click', function() {
            const skill = this.getAttribute('data-skill');

            // Fetch teammates with the selected skill using AJAX
            fetch('fetch_teammates.php?skill=' + encodeURIComponent(skill))
                .then(response => response.json())
                .then(data => {
                    const teammateList = document.getElementById('teammateList');
                    teammateList.innerHTML = ''; // Clear previous results

                    if (data.length > 0) {
                        data.forEach(teammate => {
                            const card = document.createElement('div');
                            card.classList.add('teammate-card');
                            card.innerHTML = `
                                <h4>${teammate.first_name} ${teammate.last_name}</h4>
                                <p><strong>Email:</strong> ${teammate.email}</p>
                                <p><strong>Skills:</strong> ${teammate.skills.join(', ')}</p>
                                <button class="view-profile-btn" data-id="${teammate.user_id}">View Profile</button>
                                <button class="invite-team-btn" data-id="${teammate.user_id}">Invite to Team</button>
                                <div class="project-list" id="projectList_${teammate.user_id}"></div>
                            `;
                            teammateList.appendChild(card);
                        });

                        // Attach event listeners to the "View Profile" buttons
                        document.querySelectorAll('.view-profile-btn').forEach(btn => {
                            btn.addEventListener('click', function() {
                                const userId = this.getAttribute('data-id');
                                console.log("Fetching profile for user ID:", userId);

                                // Fetch and display the profile details in the modal
                                fetch('fetch_profile.php?user_id=' + userId)
                                    .then(response => response.json())
                                    .then(profile => {
                                        if (profile.error) {
                                            alert("Profile not found!");
                                        } else {
                                            const profileDetails = document.getElementById('profileDetails');

                                            // Format the previous project information
                                            let projectsHTML = '';
                                            if (profile.projects && profile.projects.length > 0) {
                                                profile.projects.forEach(project => {
                                                    projectsHTML += `
                                                        <div class="project">
                                                            <h4>${project.project_name}</h4>
                                                            <p>${project.project_description}</p>
                                                        </div>
                                                    `;
                                                });
                                            } else {
                                                projectsHTML = '<p>No previous projects listed.</p>';
                                            }

                                            // Inject the profile details and projects into the modal
                                            profileDetails.innerHTML = `
                                                <h3>${profile.first_name} ${profile.last_name}</h3>
                                                <p><strong>Email:</strong> ${profile.email}</p>
                                                <p><strong>Skills:</strong> ${profile.skills.join(', ')}</p>
                                                <p><strong>Bio:</strong> ${profile.bio}</p>
                                                <h4>Previous Projects:</h4>
                                                ${projectsHTML}
                                            `;

                                            // Show the modal
                                            document.getElementById('profileModal').style.display = 'block';
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error fetching profile:', error);
                                        alert('Failed to fetch profile data.');
                                    });
                            });
                        });

                        // Attach event listeners to the "Invite to Team" buttons
                        document.querySelectorAll('.invite-team-btn').forEach(btn => {
                            btn.addEventListener('click', function() {
                                const userId = this.getAttribute('data-id');
                                console.log(`Inviting user ID: ${userId} to the team.`);

                                // Send POST request to invite the user and create a notification
                                fetch('invite_to_team.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                    },
                                    body: JSON.stringify({ user_id: userId })
                                })
                                .then(response => response.json())
                                .then(result => {
                                    if (result.success) {
                                        alert("Invitation sent successfully!");
                                    } else {
                                        alert("Failed to send the invitation.");
                                    }
                                })
                                .catch(error => {
                                    console.error('Error inviting user:', error);
                                    alert('An error occurred while sending the invitation.');
                                });
                            });
                        });

                    } else {
                        // No teammates found
                        teammateList.innerHTML = '<p>No teammates found with this skill.</p>';
                    }
                })
                .catch(error => console.error('Error fetching teammates:', error));
        });
    });

    // Close the modal when the close button is clicked
    document.querySelector('.close-btn').addEventListener('click', () => {
        document.getElementById('profileModal').style.display = 'none';
    });

    // Optional: Close the modal when clicking outside the modal content
    window.onclick = function(event) {
        const modal = document.getElementById('profileModal');
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    };
});
