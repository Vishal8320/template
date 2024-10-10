
<style>

    .form-check-input {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }
    .form-check-input:checked + .form-check-label .icon {
        color: red;
    }
</style>
<br><br>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <!-- Main Table -->
    <form method="POST" name="update_module">
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





        <table class="table table-bordered">
            <thead>
                <tr>
                    <th style="width: 25%;">Module & Section</th>
                    <th style="width: 75%;">Create/Update Action Page from Section List</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $moduleKey => $module)
                
                <tr>
                    <!-- Left Side: Section Creation -->
                    <td>
                        <div id="leftcontainer">
                        <div class="card shadow-sm mb-3">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="moduleName" class="form-label">Module Name</label>
                                    <div class="d-flex align-items-center section-wrapper">
                                        <input type="text" id="moduleName" name="moduleName" class="form-control" placeholder="Module name" value="{{ $module['module_name'] }}" disabled>
                                       
                                    </div>
                                </div>
                                <div class="mb-3">
                                    @php
                                    $parentcount = 1;
                                    @endphp

                                    <label for="sectionName" class="form-label">Section</label>
                                    @foreach ($module['sections'] as $sectionKey => $section)
                                    <div class="d-flex align-items-center mb-3" input-type="section">
                                        <input type="text" id="sectionName1" name="" class="form-control section-input me-2" input-type="sectionListName"  oninput="updateInputNames(null,this);" data-parent-section="{{$parentcount}}" placeholder="Section name" value="{{ $section['section_name'] }}" style="margin-bottom:10px" required>
                                        <button type="button" class="btn btn-danger btn-sm ms-2" onclick="removeInputGroupAndSection(this)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    @php $parentcount++ @endphp
                                    @endforeach
                                    <button type="button" id="duplicateSection" class="btn btn-custom-blue centered-button mb-3">More Section [+]</button>
                                </div>
                            </div>
                        </div>
                        </div>
                    </td>
                </div>
                    <!-- Right Side: Action Creation for Sections -->
                    <td>
                        <div id="sectionsContainer">
                         <!-- Iterate over each section in section_lists -->
                         @php
                         $childcount =1;
                         $parentcount2 = 1;
                        
                         @endphp
    @foreach ($module['sections'] as $sectionKey => $section)
    @foreach ($section['section_lists'] as $listKey => $listValue)
   
    <div class="section-block card shadow-sm mb-3" data-section="{{$childcount}}" data-parent-section="{{$parentcount2}}">
        <div class="card-body">
            <h6 class="section-title fw-bold">{{ $section['section_name'] }}</h6>
            <div class="mb-3">
                <label class="form-label me-2">Input Section List Name (optional)</label>
                <!-- Iterate over each item in the current list -->

              
                <div class="d-flex align-items-center section-wrapper">
                    <div class="d-flex align-items-center flex-grow-1">
                        <input type="text" name="" class="form-control section-list-name me-2"  input-type="sectionListName" oninput="updateInputNames(1,this)" placeholder="Enter Section List Name" value="{{ $listKey }}">
                        
                    </div>
                </div>
           
           




            </div>
            @foreach ($listValue as $item)
            <div class="d-flex justify-content-between align-items-center" input-type="duplicate-action">
                <!-- Iterate over each item in the current list for actions -->
               
               

                    <div class="mb-3 me-2">
                        <label class="form-label">Input Action Name</label>
                        <input type="text" name="module[{{ $module['module_name'] }}][module_data][section][{{$sectionKey}}][section_list][{{ $listKey }}][action_name][]" class="form-control action-name"  input-type="ActionName" placeholder="Enter Action Name" value="{{ $item['action_name'] }}" required>
                    </div>

                    <div class="mb-3 d-flex justify-content-center">
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeAction(this)">
                          <i class="fas fa-times"></i>
                         </button>
                    </div>
                    
                    <div class="mb-3 ms-2">
                        <label class="form-label">Input Action URL</label>
                        <input type="text" name="module[{{ $module['module_name'] }}][module_data][section][{{$sectionKey}}][section_list][{{ $listKey }}][action_url][]" class="form-control action-url" input-type="ActionUrl"  placeholder="Enter Action URL" value="{{ $item['action_url'] }}" required>
                    </div>
               
            </div>
            @endforeach
            <button type="button" class="btn btn-custom-blue centered-button" id="duplicate-action">More Action [+]</button>
            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 mt-2 me-2" 
            onclick="if (confirm('Are you sure you want to remove this section?\n refresh page to Undo this section before submit')) this.closest('.section-block').remove();">
            <i class="fas fa-times"></i>
            </button>
    
            </button>
        </div>
    </div>
    @php $childcount++ @endphp
    @endforeach
    @php $parentcount2++ @endphp
@endforeach
                        </div>
                        
                        <button type="button" class="btn btn-custom-blue mt-3 duplicate-section-list-btn centered-button" style="display:none;">More SECTION LIST [+]</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Create Button -->
        <div class="text-center mt-4">
            <button type="submit" onclick="if (confirm('Are you sure you want to Update?\n in case if case if you gave User permission from this module every user will be affected and they loss their all permission.'))" class="btn btn-custom-green btn-lg">Update</button>
        </div>
    </form>
</div>

@push('content_script')
<script>



    $(document).ready(function () {


        $(window).on('load', function () {
                // Hide all sections initially
                $('.section-block').hide();

                 // Show the section-block with data-parent-section="1"
                 $('.section-block[data-parent-section="1"]').show();
            });
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
                       moduleval = document.getElementById('moduleName').value;
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
    // Get the last section's attributes
    const lastSection = $('.section-input').last();
    const lastSectionData = {
        section: parseInt(lastSection.attr('data-section')) || 0,
        parentSection: parseInt(lastSection.attr('data-parent-section')) || 0
    };
    
    // Increment the attributes
    const newSectionData = {
        section: lastSectionData.section + 1,
        parentSection: lastSectionData.parentSection + 1
    };
    
    // Clone the last .section-input element
    const sectionClone = lastSection.clone();
    
    // Update the cloned element's data attributes
    sectionClone.attr('data-section', newSectionData.section);
    sectionClone.attr('data-parent-section', newSectionData.parentSection);

    // Replace input values with empty strings
    let htmlString = sectionClone.prop('outerHTML');
    htmlString = htmlString.replace(/value="[^"]*"/g, 'value=""');

    // Create a new input group with delete button
    const inputGroup = $(`
        <div class="d-flex align-items-center mb-3" input-type="section">
            ${htmlString}
            <button type="button" class="btn btn-danger btn-sm ms-2" onclick="removeInputGroupAndSection(this)">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `);
    
    inputGroup.insertAfter('[input-type="section"]:last');

    // Clone and update the section block
    const newSectionBlock = $('.section-block').first().clone();
    newSectionBlock.attr('data-section', newSectionData.section);
    newSectionBlock.attr('data-parent-section', newSectionData.parentSection);
    newSectionBlock.find('.section-title').text('Unnamed Section').css('color', 'red');
    newSectionBlock.find('input').val('');
    newSectionBlock.hide();

    $('#sectionsContainer').append(newSectionBlock);

    // Increment the counter for future sections
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

