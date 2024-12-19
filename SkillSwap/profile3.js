// Function to show the relevant section
function showSection(sectionId) {
  const sections = document.querySelectorAll('.section-content');
  sections.forEach(section => section.style.display = 'none');
  document.getElementById(sectionId).style.display = 'block';
}

// Save changes function placeholder (Add form submission logic here)
function saveChanges(sectionId) {
  alert('Changes saved for ' + sectionId);
  if (sectionId === 'user-details') {
      showSection('more-about-user');
  } else if (sectionId === 'more-about-user') {
      showSection('skills-section');
  } else if (sectionId === 'skills-section') {
      showSection('previous-projects');
  }
}

// Function to add skills dynamically
function addSkill() {
  const skill = document.getElementById('skills-dropdown').value;
  const skillLevel = document.getElementById('skill-level').value;
  const selectedSkills = document.getElementById('selected-skills');
  
  const skillItem = document.createElement('div');
  skillItem.textContent = skill + ' (' + skillLevel + ')';
  
  const removeButton = document.createElement('button');
  removeButton.textContent = 'Remove';
  removeButton.onclick = () => selectedSkills.removeChild(skillItem);
  
  skillItem.appendChild(removeButton);
  selectedSkills.appendChild(skillItem);
}
// Function to show specialties based on category
function showSpecialties(category) {
  const categories = ['engineering', 'it-networking', 'design'];
  
  categories.forEach(cat => {
      document.getElementById(`${cat}-specialties`).style.display = 'none';
  });

  document.getElementById(`${category}-specialties`).style.display = 'block';
}

// Save the selected skills
function saveSkills() {
  const selectedSkills = [];
  const checkboxes = document.querySelectorAll('input[name="specialty"]:checked');
  
  checkboxes.forEach(checkbox => {
      selectedSkills.push(checkbox.value);
  });

  alert('Selected skills: ' + selectedSkills.join(', '));
}
