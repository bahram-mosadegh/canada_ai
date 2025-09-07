primary_preview = '';
$(document).on('change', '.simple-file-input', function (event) {
  if (!primary_preview) {
    primary_preview = $(this).parent().find('.simple-file-preview').html();
  }
  handleFileSelect(event);
});

function updatePreview(previewContainer, fileUrl, fileName) {
  // Clear the preview container
  previewContainer.innerHTML = '';

  const fileExtension = fileName.split('.').pop().toLowerCase();
  let displayText = '';

  // Determine if it's an image based on the extension
  const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
  if (imageExtensions.includes(fileExtension)) {
    const img = document.createElement('img');
    img.src = fileUrl;
    previewContainer.appendChild(img);
  } else {
    // Map common file extensions to more readable formats
    const fileExtensionMap = {
      'xlsx': 'Excel <i class="fa-solid fa-file-excel"></i>',
      'xls': 'Excel <i class="fa-solid fa-file-xls"></i>',
      'pdf': 'PDF <i class="fa-solid fa-file-pdf"></i>',
      'doc': 'Word <i class="fa-solid fa-file-doc"></i>',
      'docx': 'Word <i class="fa-solid fa-file-word"></i>',
      'txt': 'Text <i class="fa-solid fa-file-lines"></i>',
      'zip': 'ZIP <i class="fa-solid fa-file-zipper"></i>',
      'ppt': 'PowerPoint <i class="fa-solid fa-file-ppt"></i>',
      'pptx': 'PowerPoint <i class="fa-solid fa-file-ppt"></i>',
      'csv': 'CSV <i class="fa-solid fa-file-csv"></i>',
      // Add more mappings as needed
    };

    // Use the mapped name if available, otherwise show the extension in uppercase
    displayText = fileExtensionMap[fileExtension] || fileExtension.toUpperCase();
    
    const fileInfo = document.createElement('span');
    fileInfo.innerHTML = displayText;
    previewContainer.appendChild(fileInfo);
  }
}

function handleFileSelect(event) {
  const input = event.target;
  const label = input.nextElementSibling;
  const previewContainer = label.querySelector('.simple-file-preview');
  const file = input.files[0];

  if (file) {
    const fileUrl = URL.createObjectURL(file);
    const fileName = file.name;

    // Update the preview using the helper function
    updatePreview(previewContainer, fileUrl, fileName);

    // Add the 'filled' class to the label to change the border style
    label.classList.add('filled');
  } else {
    // If no file is selected, reset the preview and border style
    label.classList.remove('filled');
    previewContainer.innerHTML = primary_preview;
  }
}

function displayPreloadedFile(id, fileUrl, download_icon = true, edit_icon = true, custom_icons = []) {
  const input = document.getElementById(id);
  if (input) {
    const label = input.nextElementSibling;
    const previewContainer = label.querySelector('.simple-file-preview');

    // Extract file name from the URL
    const fileName = fileUrl.split('/').pop();

    // Update the preview using the helper function
    updatePreview(previewContainer, fileUrl, fileName);

    // Add the 'filled' class to the label to change the border style
    label.classList.add('filled');

    // Ensure there's no previous icons container (for repeated calls)
    const existingIconsContainer = label.parentNode.querySelector('.simple-file-icons-container');
    if (existingIconsContainer) {
      existingIconsContainer.remove();
    }

    // Add download and edit icons under the preview
    addIcons(label.parentNode, fileUrl, download_icon, edit_icon, custom_icons);
  }
}

function addIcons(container, fileUrl, download_icon = true, edit_icon = true, custom_icons = []) {
  // Create a container for the icons
  const iconsContainer = document.createElement('div');
  iconsContainer.className = 'simple-file-icons-container';

  const downloadIcon = document.createElement('span');
  downloadIcon.onclick = function() { window.open(fileUrl, '_blank').focus(); };;
  downloadIcon.innerHTML = '<i class="fa-solid fa-download"></i>';

  const editIcon = document.createElement('span');
  editIcon.innerHTML = '<i class="fa-solid fa-pen-to-square"></i>';

  // When the edit icon is clicked, trigger the file input click event
  editIcon.addEventListener('click', () => {
    container.querySelector('.simple-file-input').click(); // Simulate click on file input
  });

  // Append icons to the container
  if (edit_icon) {
    iconsContainer.appendChild(editIcon);
  }

  if (download_icon) {
    iconsContainer.appendChild(downloadIcon);
  }

  custom_icons.forEach(htmlString => {
    let tempElement = document.createElement('div');
    tempElement.innerHTML = htmlString;
    iconsContainer.appendChild(tempElement.firstChild);
  });

  // Append the icons container to the parent container (outside the label)
  container.appendChild(iconsContainer);
}