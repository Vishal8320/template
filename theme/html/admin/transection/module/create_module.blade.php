


<div class="container">

          @isset($output)

          @if($output['status'] == true)
          
          @php 
           $class = "alert-success";
           $icon = "far fa-check-circle me-2";
          @endphp

          @else
          
          @php 
          $class = "alert-danger";
          $icon = "fa-solid fa-circle-exclamation me-2";
         @endphp

          @endif
          @endisset

    @if(!empty($output))
        <!-- Success Alert -->
        <div class="alert {{$class}} d-flex align-items-center " role="alert">
            <i class="{{$icon}}"></i>
            <div>
                @if($output['status'] == true)
                {{$output['message']}}
                
                
            @else
    
                @if(!is_array($output['error']))
                {{$output['error']}}
                @else
                {{$output['error'][0]}}
                @endif
    
    
            @endif
            </div>
        </div>
    @endif



    <!-- Main Table -->
    <form method="POST" id="create_module" name="create_module">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th style="width: 25%;">Module & Section</th>
                <th style="width: 75%;">Create Action Page from Section List</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <!-- Left Side: Section Creation -->
                <td>
                    <div id="leftcontainer">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="moduleName" class="form-label">Creating Module</label>
                                <input type="text" id="modulName" class="form-control" placeholder="Module name" required>
                            </div>
                            <div class="mb-3">
                                <label for="sectionName" class="form-label">Creating Section</label>
                                    <div class="d-flex align-items-center mb-3" input-type="section">
                                    <input type="text" id="sectionName" class="form-control section-input me-2" data-parent-section="1"
                                     oninput="updateInputNames(null,this);"
                                    placeholder="Section name" required>
                                    </div>
                            </div>
                            <button  type="button" id="duplicateSection" class="btn btn-custom-blue centered-button mb-3">More Section [+]</button>
                        </div>
                    </div>
                    </div>
                </td>
              
                <!-- Right Side: Action Creation for Sections -->
                <td>
                    <div id="sectionsContainer">
                        <!-- Default Section Form -->
                        <div class="section-block card shadow-sm mb-3" data-section="1" data-parent-section="1">
                            <div class="card-body">

                                <h6 class="section-title fw-bold">Unnamed Section</h6> <!-- Default Name -->
                                <div class="mb-3">
                                    <label class="form-label">Input Section List Name (optional)</label>
                                    <!-- HTML Structure with Inline JavaScript -->
                                    <input type="text" id="sectionListName" name="" class="form-control section-list-name" input-type="sectionListName" oninput="updateInputNames(1,this)" placeholder="Enter Section List Name">


                                </div>
                                <div class="d-flex justify-content-between align-items-center" input-type="duplicate-action">
                                    <!-- Input Action Name -->
                                    <div class="mb-3 me-2">
                                        <label class="form-label">Input Action Name</label>
                                        <input type="text" id="actionName" name="" input-type="ActionName" class="form-control action-name" placeholder="Enter Action Name" required>
                                    </div>
                                
                                    <!-- Input Action URL -->
                                    <div class="mb-3 ms-2">
                                        <label class="form-label">Input Action URL</label>
                                        <input type="text" id="actionUrl" name="" input-type="ActionUrl" class="form-control action-url" placeholder="Enter Action URL" required>
                                    </div>
                                </div>
                                
                                <button  type="button" class="btn btn-custom-blue centered-button" id="duplicate-action">More Action [+]</button>
                            </div>
                        </div>
                    </div>
                    <button  type="button" class="btn btn-custom-blue mt-3 duplicate-section-list-btn centered-button" style="display:none;">More SECTION LIST [+]</button>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Create Button -->
    <div class="text-center mt-4">
        <button id="createBtn" type="submit" class="btn btn-custom-green btn-lg">Create</button>
    </div>
    </form>
</div>

@push('content_script')
<script>
    $(document).ready(function () {
        let sectionCounter = 2; // Counter to keep track of section numbers

        // Function to update section title when the input value changes
        $(document).on('input', '.section-input', function () {
            const sectionNumber = $(this).attr('data-parent-section');
            const sectionValue = $(this).val().trim();

            const sectionTitle = $(`.section-block[data-parent-section="${sectionNumber}"] .section-title`);
            if (sectionValue !== "") {
                sectionTitle.text(sectionValue).css('color', 'black'); // Set the title to section input value
                $('.duplicate-section-list-btn').show(); // Show button globally when any section has a name
            } else {
                sectionTitle.text('Unnamed Section').css('color', 'red'); // Set default title if input is empty
                $('.duplicate-section-list-btn').hide(); // Hide button if no section name
            }
        });


              // Function to update input names dynamically
              window.updateInputNames = function (type, inputElement) {
                
                 // Get the container with all inputs
                
                const container = document.getElementById('sectionsContainer');

                let parentSection;
                let sectionBlocks; // This will be a NodeList or array depending on the condition
                let inputsToUpdate;

                if (type) {
                    
                    // Get attributes from parent section and data section
                    parentSection = inputElement.closest(".section-block[data-parent-section]").getAttribute('data-parent-section');
                    const sectionId = inputElement.closest(".section-block[data-section]").getAttribute('data-section');

                    const leftcontainer = document.querySelector(`#leftcontainer [data-parent-section="${parentSection}"]`);
                    const clonedContainer = leftcontainer.cloneNode(true); // true for deep clone
                    sectionValue = clonedContainer.value;
    
                    sectionBlocks = [container.querySelector(`.section-block[data-section="${sectionId}"]`)];
                    
                } else {

                        sectionValue = inputElement.value; // Get the input value
                        parentSection = inputElement.getAttribute('data-parent-section');
                        sectionBlocks = document.querySelectorAll(`.section-block[data-parent-section="${parentSection}"]`);
                }
                        moduleval = document.getElementById('modulName').value;
                        module_value = (moduleval === null || moduleval.trim() === '') ? 'null' : moduleval;

                    // Loop through each section block
                    sectionBlocks.forEach(block => {
                        if (block) { // Ensure block is not null
                            const dataSectionId = block.getAttribute('data-section');

                            // Get all inputs that belong to the current section block
                            inputsToUpdate = block.querySelectorAll('input');

                            // Update each input's name attribute
                            inputsToUpdate.forEach(function (input) {
                                const nameType = input.getAttribute('input-type');
                              
                                // Get the sectionListName value for the current section block
                                const sectionListNameInput = block.querySelector('input[input-type="sectionListName"]');
                                const sectionListName = sectionListNameInput ? (sectionListNameInput.value.trim() === '' ? 'null' : sectionListNameInput.value) : 'null';


                            
                                 if (nameType === "ActionName") {
                                input.name = `module[${ module_value}][module_data][section][${sectionValue}][section_list][${sectionListName}][action_name][]`;
                                } else if (nameType === "ActionUrl") {
                                    input.name = `module[${ module_value}][module_data][section][${sectionValue}][section_list][${sectionListName}][action_url][]`;
                                }
                                
                            });
                        }
                    });
                    }



        

        // Duplicate section inputs
        $('#duplicateSection').click(function () {
            const sectionClone = $('.section-input').first().clone();
            sectionClone.attr('data-section', sectionCounter);
            sectionClone.attr('data-parent-section', sectionCounter); 
            sectionClone.val('');

          // Create a new input group with delete button
          const inputGroup = $(`
            <div class="d-flex align-items-center mb-3" input-type="section" data-section="${sectionCounter}">
                ${sectionClone.prop('outerHTML')}
                <button type="button" class="btn btn-danger btn-sm ms-2" onclick="removeInputGroupAndSection(this)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `);
        
        inputGroup.insertAfter('[input-type="section"]:last');

        const newSectionBlock = $('.section-block').first().clone();
        newSectionBlock.attr('data-section', sectionCounter);
        newSectionBlock.attr('data-parent-section', sectionCounter);
        newSectionBlock.find('.section-title').text('Unnamed Section').css('color', 'red');
        newSectionBlock.find('input').val('');
        newSectionBlock.hide();

        $('#sectionsContainer').append(newSectionBlock);

        sectionCounter++;
        });

            // Function to remove an input group and its corresponding section block
            window.removeInputGroupAndSection = function(button) {
                const inputGroup = $(button).closest('.d-flex');
                const parentSection = inputGroup.find('.section-input').attr('data-parent-section');

                // Remove the input group
                inputGroup.remove();

                // Remove the corresponding section block
                $(`.section-block[data-parent-section="${parentSection}"]`).remove();
            }

        // Show corresponding section on focus of section input
        $(document).on('focus', '.section-input', function () {
            const sectionNumber = $(this).attr('data-section');
            const parentSection = $(this).attr('data-parent-section');

            $('.section-block').hide(); // Hide all sections
            $(`.section-block[data-parent-section="${parentSection}"]`).show(); // Show all sections with the same parent-section
        });

        // Function to duplicate actions within a section
        $(document).on('click', '#duplicate-action', function () {

        const card = $(this).closest('.card-body');
        const dFlexContainer = card.find('.d-flex[input-type="duplicate-action"]');
        // Clone the action HTML
        const actionHTML = dFlexContainer.clone().first();
        // Add close icon for removing section
        actionHTML.find('[input-type="ActionName"]').closest('div').after(`
               <div class="mb-3 d-flex justify-content-center">

                  <button type="button" class="btn btn-danger btn-sm" onclick="removeAction(this)">
                    <i class="fas fa-times"></i>
                </button>

                   
                 </div>
            `);
        // Clear the input values in the cloned action HTML
        actionHTML.find('input').val('');

        // Insert the cloned action HTML before the current button
        $(this).before(actionHTML);

        });

        window.removeAction = function (button) {
        // Find the closest ancestor with the specified input-type
        const closestElement = button.closest('[input-type="duplicate-action"]');
            if (closestElement) {
                closestElement.remove();
            }
        }



        // Duplicate SECTION_LIST Button
        $(document).on('click', '.duplicate-section-list-btn', function () {
    // Get the first visible section block
    const activeSectionBlock = $('.section-block:visible').first();

    const sectionNumber = sectionCounter++;
    const parentSection = activeSectionBlock.attr('data-parent-section');

    // Clone the first visible section block
    const sectionClone = activeSectionBlock.clone();
    sectionClone.attr('data-section', sectionNumber);
    sectionClone.attr('data-parent-section', parentSection); // Keep the parent-section attribute

    // Find the first .d-flex container with input-type="duplicate-action" within the cloned section
    const firstActionContainer = sectionClone.find('.d-flex[input-type="duplicate-action"]').first();

    // Ensure only the first .d-flex container with input-type="duplicate-action" is cloned
    sectionClone.find('.d-flex[input-type="duplicate-action"]').not(firstActionContainer).remove();

    // Add close icon for removing section
    sectionClone.find('.card-body').append(`
        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 mt-2 me-2" onclick="this.closest('.section-block').remove()">
            <i class="fas fa-times"></i>
        </button>
    `);

    // Clear all input values in the cloned section
    sectionClone.find('input').val('');

    // Append the cloned section block to the container
    $('#sectionsContainer').append(sectionClone);
});

    });
</script>


@endpush

