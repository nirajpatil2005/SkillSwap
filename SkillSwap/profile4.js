document.addEventListener("DOMContentLoaded", function () {
  // Function to show the selected section
  function showSection(sectionId) {
      const sections = document.querySelectorAll('.section-content');
      sections.forEach(section => {
          section.style.display = 'none'; // Hide all sections
      });

      const activeSection = document.getElementById(sectionId);
      if (activeSection) {
          activeSection.style.display = 'block'; // Show the selected section
      }
  }

  // Function to show specialties based on the selected category
  function showSpecialties(category) {
      const specialtyLists = document.querySelectorAll('.specialty-list');
      specialtyLists.forEach(list => {
          list.style.display = 'none'; // Hide all specialty lists
      });

      const selectedSpecialtyList = document.getElementById(category + '-specialties');
      if (selectedSpecialtyList) {
          selectedSpecialtyList.style.display = 'block'; // Show the selected specialty list
      }
  }

  // Event listeners for section navigation buttons
  document.querySelectorAll('.profile-nav button').forEach(button => {
      button.addEventListener('click', function () {
          const sectionId = this.textContent.toLowerCase().replace(/\s+/g, '-'); // Convert button text to section ID
          showSection(sectionId);
      });
  });

  // Event listeners for specialty category clicks
  const specialtiesCategories = document.querySelectorAll('.skills-categories li');
  specialtiesCategories.forEach(category => {
      category.addEventListener('click', function () {
          const categoryId = this.textContent.toLowerCase().replace(/\s+/g, '-'); // Convert category name to ID
          showSpecialties(categoryId);
      });
  });
});
