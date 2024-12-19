document.addEventListener('DOMContentLoaded', function () {
  // Function to toggle visibility of sections
  function toggleSection(sectionId) {
    const sections = document.querySelectorAll('.section'); // Select all section elements
    sections.forEach(section => {
      if (section.id === sectionId) {
        section.classList.toggle('hidden'); // Show or hide selected section
      } else {
        section.classList.add('hidden'); // Hide other sections
      }
    });
  }

  // Enable edit mode for a specific section
  function enableEdit(sectionName) {
    const editForm = document.getElementById(`edit-${sectionName}`);
    const viewSection = document.getElementById(`view-${sectionName}`);
    
    // Check if both form and view sections exist
    if (editForm && viewSection) {
      editForm.classList.toggle('hidden'); // Toggle edit form visibility
      viewSection.classList.toggle('hidden'); // Toggle view section visibility
    } else {
      console.error(`Unable to find edit or view section for: ${sectionName}`);
    }
  }

  // Add event listeners to the tab buttons
  document.querySelectorAll('.tab-button').forEach(button => {
    button.addEventListener('click', function () {
      const sectionId = this.getAttribute('data-section-id'); // Use a data attribute for section identification
      if (sectionId) {
        toggleSection(sectionId); // Toggle visibility of the selected section
      } else {
        console.error('No section ID found for this button.');
      }
    });
  });

  // Dynamic handling for edit buttons
  const editButtons = [
    { buttonId: 'edit-details-btn', section: 'details' },
    { buttonId: 'edit-more-about-btn', section: 'more-about' },
    { buttonId: 'edit-skills-btn', section: 'skills' },
    { buttonId: 'edit-projects-btn', section: 'projects' }
  ];

  // Add event listeners to each edit button
  editButtons.forEach(({ buttonId, section }) => {
    const button = document.getElementById(buttonId);
    if (button) {
      button.addEventListener('click', () => enableEdit(section));
    } else {
      console.warn(`Edit button with ID "${buttonId}" not found.`);
    }
  });
});
