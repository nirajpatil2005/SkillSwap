document.addEventListener('DOMContentLoaded', () => {
    const showHardwareBtn = document.getElementById('showHardware');
    const showSoftwareBtn = document.getElementById('showSoftware');
    const hardwareProjects = document.getElementById('hardwareProjects');
    const softwareProjects = document.getElementById('softwareProjects');

    // Show Hardware Projects
    showHardwareBtn.addEventListener('click', () => {
        hardwareProjects.style.display = 'block';
        softwareProjects.style.display = 'none';
    });

    // Show Software Projects
    showSoftwareBtn.addEventListener('click', () => {
        softwareProjects.style.display = 'block';
        hardwareProjects.style.display = 'none';
    });
});
