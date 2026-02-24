@extends('layouts.home')

@section('content')
    <!--=================================
                    breadcrumb -->
    <div class="bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="index.html"> <i class="fas fa-home"></i> </a></li>
                        <li class="breadcrumb-item"> <i class="fas fa-chevron-right"></i> <a href="#">Pages</a></li>
                        <li class="breadcrumb-item active"> <i class="fas fa-chevron-right"></i> <span>My profile</span>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!--=================================
                      breadcrumb -->

    <!--=================================
                      subit property -->
    <section class="space-ptb">

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title d-flex align-items-center">
                        <h2>Submit Property</h2>
                    </div>
                    <div class="row">
                        <div class="col-12">

                            <style>
                                form {
                                    width: 90%;
                                    max-width: 800px;
                                    margin: 20px auto;
                                    background: #fff;
                                    padding: 20px;
                                    border-radius: 8px;
                                    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                                }
                                .newsletter form {
                                    padding: 0;
                                }

                                .form-step {
                                    display: none;
                                }

                                .form-step.active {
                                    display: block;
                                }

                                .form-navigation {
                                    display: flex;
                                    justify-content: space-between;
                                    margin-top: 20px;
                                }

                                button {
                                    background: white;
                                    color: black;
                                    border: 2px solid #26ae61;
                                    padding: 10px 20px;
                                    cursor: pointer;
                                    border-radius: 5px;
                                    transition: background-color 0.3s, color 0.3s;
                                }

                                button.selected {
                                    background: #26ae61;
                                    color: white;
                                }

                                button:disabled {
                                    background: #ccc;
                                    cursor: not-allowed;
                                }

                                button:hover:not(:disabled) {
                                    background: #1e944f;
                                    color: white;
                                }

                                input,
                                select,
                                textarea {
                                    width: 100%;
                                    margin-bottom: 10px;
                                    padding: 10px;
                                    border: 1px solid #ccc;
                                    border-radius: 5px;
                                }

                                label {
                                    font-weight: bold;
                                    display: block;
                                    margin-bottom: 5px;
                                }

                                #childGroup {
                                    margin-top: 20px;
                                }

                                #dynamicChildButtons button {
                                    margin: 5px;
                                }

                                .amenities-container {
                                    display: grid;
                                    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                                    gap: 10px;
                                    margin-top: 10px;
                                }

                                .amenity {
                                    display: flex;
                                    align-items: center;
                                    gap: 10px;
                                }

                                .amenity i {
                                    color: #26ae61;
                                    font-size: 1.2em;
                                }

                                .amenity input[type="checkbox"] {
                                    margin-right: 10px;
                                }

                                #locationInput {
                                    width: 100%;
                                    padding: 10px;
                                    margin-bottom: 10px;
                                    border: 1px solid #ccc;
                                    border-radius: 5px;
                                }

                                #map {
                                    width: 100%;
                                    height: 400px;
                                    margin-top: 20px;
                                    border: 1px solid #ccc;
                                    border-radius: 5px;
                                }
                            </style>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif


                            <form id="propertyForm" method="POST" action="{{ route('property.store') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <!-- Step 1: Property Purpose -->
                                <input type="hidden" name="propertyType" id="propertyType" required value="1">
                                <div class="form-step" data-step="1" data-target="propertyType">
                                    
                                    <!-- <h2>Do You Want To</h2> -->
                                    <a href="{{ route('home') }}" id="cancelBtn" class="btn btn-outline-success d-none" role="button"> X </a>
                                    <h2>List your property now!</h2>
                                    <button type="button" class="selectable selected " data-value="1">Sell Property</button>
                                    <button type="button" class="selectable" data-value="2">Rent Property</button>
                                    
                                </div>

                                <!-- Step 2: Property Type -->

                                <div class="form-step" data-step="2" data-target="property_category_id">
                                    <h2>Type Of Your Property</h2>
                                    <input type="hidden" name="property_category_id" id="property_category_id" required>
                                    <input type="hidden" name="child_type_id" id="child_type_id" required>
                                    <div id="parentGroup">
                                        <button type="button" class="selectable" data-type="residential"
                                            data-value="1">Residential</button>
                                        <button type="button" class="selectable" data-type="commercial"
                                            data-value="2">Commercial</button>
                                        <button type="button" class="selectable" data-type="industrial"
                                            data-value="3">Industrial</button>
                                        <button type="button" class="selectable" data-type="land"
                                            data-value="4">Land</button>
                                    </div>

                                    <div id="childGroup" style="display: none; margin-top: 20px;">
                                        <h3>Let's Be A Little More Specific</h3>
                                        <div id="dynamicChildButtons"></div>
                                    </div>
                                </div>
                                <style>
                                    #mapnew {
                                        width: 100%;
                                        /* Adjust as needed */
                                        height: 450px;
                                        /* Adjust as needed */
                                    }
                                </style>
                                <!-- Step 3: Location -->
                                <div class="form-step" data-step="3">
                                    <h2>Property Title</h2>
                                    <input type="text" name="propertyName" placeholder="Enter Property Name" required>
                                    <h2>Exact location/address</h2>
                                    <input id="locationInput" type="text" name="address" placeholder="Type a location"
                                        required>
                                    <input type="hidden" name="latitude" id="latitude">
                                    <input type="hidden" name="longitude" id="longitude">
                                    <div id="mapnew"></div>
                                </div>

                                <!-- Step 4: Property Details -->
                                <input type="hidden" name="bedrooms" id="bedrooms" required>
                                <input type="hidden" name="bathrooms" id="bathrooms" required>
                                <div class="form-step" data-step="4">
                                    <h2>Tell Us More About Your Property</h2>
                                    <label>Number of Bedrooms *</label>
                                    <button type="button" class="selectable" data-group="bedrooms" data-value="0">
                                        {{ translate('Studio') }}
                                    </button>
                                    <button type="button" class="selectable" data-group="bedrooms"
                                        data-value="1">1</button>
                                    <button type="button" class="selectable" data-group="bedrooms"
                                        data-value="2">2</button>
                                    <button type="button" class="selectable" data-group="bedrooms"
                                        data-value="3">3</button>
                                    <button type="button" class="selectable" data-group="bedrooms"
                                        data-value="4">4</button>
                                    <button type="button" class="selectable" data-group="bedrooms"
                                        data-value="5">5+</button>
                                    <!-- <button type="button" class="selectable" data-group="bedrooms"
                                        data-value="6">6+</button> -->

                                    <label>Number of Bathrooms *</label>
                                    <button type="button" class="selectable" data-group="bathrooms"
                                        data-value="1">1</button>
                                    <button type="button" class="selectable" data-group="bathrooms"
                                        data-value="2">2</button>
                                    <button type="button" class="selectable" data-group="bathrooms"
                                        data-value="3">3</button>
                                    <button type="button" class="selectable" data-group="bathrooms"
                                        data-value="4">4</button>
                                    <button type="button" class="selectable" data-group="bathrooms"
                                        data-value="5">5+</button>
                                    <!-- <button type="button" class="selectable" data-group="bathrooms"
                                        data-value="6">6+</button> -->

                                </div>

                                <!-- Step 5: Area and Unit Details -->
                                <div class="form-step" data-step="5">
                                    <h2>Area and Unit Details</h2>

                                    <label>Built-Up Area *</label>
                                    <input type="text" name="builtArea" placeholder="sq ft" required>

                                    <label>Plot Area</label>
                                    <input type="text" name="plotArea" placeholder="sq ft">

                                    <label>Unit No.</label>
                                    <input type="text" name="unitNo" placeholder="unit No.">

                                    <label>Floor No.</label>
                                    <input type="text" name="floorNo" placeholder="floor No.">
                                </div>

                                <!-- Step 6: Additional Property Features -->
                                <input type="hidden" name="furnished" id="furnished">
                                <input type="hidden" name="balcony" id="balcony">
                                <div class="form-step" data-step="6">
                                    <h2>Additional Property Features</h2>

                                    <label>Is your property furnished? *</label>
                                    <button type="button" class="selectable" data-group="furnished"
                                        data-value="yes">Yes</button>
                                    <button type="button" class="selectable" data-group="furnished"
                                        data-value="no">No</button>
                                    <button type="button" class="selectable" data-group="furnished"
                                        data-value="semi">Semi</button>

                                    <label>Do you have a balcony in this property? *</label>
                                    <button type="button" class="selectable" data-group="balcony"
                                        data-value="1">Yes</button>
                                    <button type="button" class="selectable" data-group="balcony"
                                        data-value="0">No</button>
                                </div>

                                <!-- Step 7: Community Features -->
                                <div class="form-step" data-step="7">
                                    <h2>Community Features</h2>

                                    <label>Is the community gated? *</label>
                                    <input type="hidden" name="community" id="community">
                                    <button type="button" class="selectable" name="community"
                                        data-value="1">Yes</button>
                                    <button type="button" class="selectable" name="community"
                                        data-value="0">No</button>

                                    <label>Property View</label>
                                    <select name="view">
                                        <option value="">Select</option>
                                        <option value="1">Sea</option>
                                        <option value="2">City</option>
                                        <option value="3">Garden</option>
                                    </select>

                                    <label>Number of Parkings</label>
                                    <select name="parking">
                                        <option value="">Select</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>

                                    <!-- <input type="hidden" name="status" id="status" required> -->
                                    <label>Property Status *</label>
                                    <!-- <select name="status"> -->
                                    <select name="status" id="status" required>
                                        <option value="">Choose</option>
                                        <option value="1">Vacant</option>
                                        <option value="2">Vacant on Transfer</option>
                                        <option value="3">Rented</option>
                                        <option value="4">Off Plan/Under Construction</option>
                                    </select>

                                    <label>Property Description</label>
                                    <textarea name="any_upgrades" placeholder="Enter details"></textarea>
                                </div>
                                <style>
                                    .amenity-item {
                                        display: flex;
                                        align-items: center;
                                        gap: 8px;
                                        font-weight: 500;
                                        color: #333;
                                        cursor: pointer;
                                        padding: 10px;
                                        border-radius: 8px;
                                        transition: background 0.3s ease;
                                    }

                                    .amenity-item:hover {
                                        background: #f9f9f9;
                                    }

                                    .amenity-icon {
                                        font-size: 20px;
                                        color: #26ae61;
                                        /* Theme color */
                                    }

                                    input[type="checkbox"] {
                                        width: 16px;
                                        height: 16px;
                                        accent-color: #26ae61;
                                        /* Theme color */
                                    }

                                    .row {
                                        display: flex;
                                        flex-wrap: wrap;
                                    }
                                    #selectAllAmenitiesBtn.active,
                                    #selectAllAmenitiesBtn:active {
                                        background-color: #198754 !important; /* Bootstrap primary color */
                                        color: #fff !important;
                                        border-color: #198754 !important;
                                    }
                                    .aminity-select {
                                        background: #1b1b1b1b;
                                    }

                                </style>
                                <!-- Step 8: Amenities And Facilities -->
                                <div class="form-step" data-step="8">
                                    <h2>Amenities And Facilities</h2>
                                    <div class="mb-3 d-flex justify-content-end">
                                        <!-- <button type="button" class="btn btn-sm btn-outline-success" id="selectAllAmenitiesBtn">
                                            <i class="fa fa-check-square me-1"></i> Select All Amenities
                                        </button> -->
                                        <label class="amenity-item aminity-select">
                                            <input type="checkbox" class="mb-0" id="selectAllAmenitiesBtn" >
                                            <i class="fa fa-clock amenity-icon"></i>
                                            <span>Select All</span>
                                        </label>
                                    </div>

                                    <div class="row amenity-checkboxes">
                                        <div class="col-md-3 col-sm-6 mb-3">
                                            <label class="amenity-item">
                                                <input type="checkbox" name="amenities[]" value="1">
                                                <i class="fa fa-clock amenity-icon"></i>
                                                <span>24 Hour Service</span>
                                            </label>
                                        </div>
                                        <div class="col-md-3 col-sm-6 mb-3">
                                            <label class="amenity-item">
                                                <input type="checkbox" name="amenities[]" value="2">
                                                <i class="fa fa-store amenity-icon"></i>
                                                <span>Mall</span>
                                            </label>
                                        </div>
                                        <div class="col-md-3 col-sm-6 mb-3">
                                            <label class="amenity-item">
                                                <input type="checkbox" name="amenities[]" value="3">
                                                <i class="fa fa-wind amenity-icon"></i>
                                                <span>Air Conditioning</span>
                                            </label>
                                        </div>
                                        <div class="col-md-3 col-sm-6 mb-3">
                                            <label class="amenity-item">
                                                <input type="checkbox" name="amenities[]" value="4">
                                                <i class="fa fa-car amenity-icon"></i>
                                                <span>Parking</span>
                                            </label>
                                        </div>
                                        <div class="col-md-3 col-sm-6 mb-3">
                                            <label class="amenity-item">
                                                <input type="checkbox" name="amenities[]" value="5">
                                                <i class="fa fa-dumbbell amenity-icon"></i>
                                                <span>Gym</span>
                                            </label>
                                        </div>
                                        <div class="col-md-3 col-sm-6 mb-3">
                                            <label class="amenity-item">
                                                <input type="checkbox" name="amenities[]" value="6">
                                                <i class="fa fa-swimmer amenity-icon"></i>
                                                <span>Swimming Pool</span>
                                            </label>
                                        </div>
                                        <div class="col-md-3 col-sm-6 mb-3">
                                            <label class="amenity-item">
                                                <input type="checkbox" name="amenities[]" value="7">
                                                <i class="fa fa-train amenity-icon"></i>
                                                <span>Near Metro</span>
                                            </label>
                                        </div>
                                        <div class="col-md-3 col-sm-6 mb-3">
                                            <label class="amenity-item">
                                                <input type="checkbox" name="amenities[]" value="8">
                                                <i class="fa fa-child amenity-icon"></i>
                                                <span>Children Nursery</span>
                                            </label>
                                        </div>
                                    </div>

                                </div>

                                <!-- Step 9: Pricing Details -->
                                <input type="hidden" name="communityFee" id="communityFee">
                                <input type="hidden" name="mortgaged" id="mortgaged">
                                <div class="form-step" data-step="9">
                                    <h2>Provide Pricing Details For Your Property</h2>

                                    <label id="priceLabel">Selling Price *</label>
                                    <input type="text" name="price" id="myPrice" placeholder="AED" required>

                                    <div id="anualServiceCharge">
                                        <label>Annual Service Charges *</label>
                                        <input type="text" name="asc" id="myAsc" placeholder="AED per sq ft" required>
                                    </div>

                                    <label>Is this property mortgaged? *</label>
                                    <div>
                                        <button type="button" class="selectable" data-group="mortgaged"
                                            data-value="1">Yes</button>
                                        <button type="button" class="selectable" data-group="mortgaged"
                                            data-value="0">No</button>
                                    </div>
                                </div>

                                <style>
                                    .form-check {
                                        display: flex;
                                        align-items: center;
                                    }

                                    .form-check-input {
                                        width: 18px;
                                        height: 18px;
                                    }

                                    .form-check-label {
                                        margin-left: 8px;
                                        /* Adjust spacing */
                                        display: flex;
                                        align-items: center;
                                        font-size: 14px;
                                        color: #333;
                                        /* Adjust to match theme */
                                        cursor: pointer;
                                        line-height: 25px;

                                    }


                                    .pricing-link {
                                        margin-top: 10px;
                                    }

                                    .pricing-link a {
                                        color: #26ae61;
                                        text-decoration: none;
                                        font-weight: 500;
                                    }

                                    .pricing-link a:hover {
                                        text-decoration: underline;
                                    }
                                </style>

                                <!-- Step 10: Images and Video -->
                                <div class="form-step" data-step="10">
                                    <h2>Images and Video</h2>

                                    <!-- Upload Images Section -->
                                    <div id="image-upload-section">
                                        <label>Upload Images</label>
                                        <input type="file" name="pictures[]" multiple>
                                        <p>Max 10 images allowed</p>
                                    </div>

                                    <!-- Checkbox to Request Photographer -->
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="request-photographer"
                                            name="needs_photographer" value="1">
                                        <label class="form-check-label" for="request-photographer">
                                            I don’t have pictures, request a professional photographer
                                        </label>
                                    </div>

                                    <!-- Link to Pricing Page -->
                                    <!-- <div class="pricing-link">
                                        <a href="{{ route('services') }}#photography-pricing" target="_blank">View
                                            photography pricing</a>
                                    </div> -->


                                </div>

                                <!-- Navigation Buttons -->
                                <div class="form-navigation">
                                <style>
                                    #cancelBtn:hover {
                                        background-color: red !important; /* Bootstrap success color */
                                        color: #fff !important; /* White text */
                                        border-color: red !important;
                                    }
                                    a#cancelBtn {
                                        padding: 4px 10px;
                                        height: fit-content;
                                        color: red;
                                        border-color: red;
                                        border-radius: 100px;
                                        position: absolute;
                                        right: 0;
                                        top: 0;
                                        margin: 10px;
                                    }

                                    form#propertyForm {
                                        position: relative;
                                    }
                                </style>
                                <!-- <a href="{{ route('home') }}" id="cancelBtn"
                                    class="btn btn-outline-success"
                                    role="button">
                                    X
                                    </a> -->

                                    <button type="button" id="prevBtn">Previous</button>
                                    <button type="button" id="nextBtn" disabled>Next</button>
                                    <button type="submit" id="submitBtn">Submit</button>
                                </div>
                            </form>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--=================================
                    Submit Property -->

    <!--=================================
                      My profile -->
@endsection


<script>
document.addEventListener('DOMContentLoaded', function () {
    const steps = document.querySelectorAll('.form-step');
    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');
    const submitBtn = document.getElementById('submitBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    let currentStep = 1;

    // Show first step
    showStep(currentStep);

    // Next button click - Use stopImmediatePropagation to prevent other handlers
    nextBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        
        // Validate current step before moving forward
        if (validateCurrentStep()) {
            if (currentStep < steps.length) {
                currentStep++;
                showStep(currentStep);
            }
        }
        return false;
    }, true); // Use capture phase

    // Previous button click
    prevBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        
        // Clear errors when going back
        clearCurrentStepErrors();
        
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
        }
        return false;
    }, true);

    // Function to clear errors from current step
    function clearCurrentStepErrors() {
        const currentStepElement = document.querySelector(`.form-step[data-step="${currentStep}"]`);
        if (currentStepElement) {
            const oldErrors = currentStepElement.querySelectorAll('.step-error');
            oldErrors.forEach(error => error.remove());
        }
    }

    // Function to show specific step
    function showStep(step) {
        steps.forEach((stepElement, index) => {
            stepElement.classList.remove('active');
            if (index + 1 === step) {
                stepElement.classList.add('active');
            }
        });

        // Update button visibility
        prevBtn.style.display = step === 1 ? 'none' : 'inline-block';
        cancelBtn.style.display = step === 1 ? 'none' : 'inline-block';
        nextBtn.style.display = step === steps.length ? 'none' : 'inline-block';
        submitBtn.style.display = step === steps.length ? 'inline-block' : 'none';

        // Clear errors when showing new step
        clearCurrentStepErrors();
        
        // Check if current step buttons are selected
        checkStepCompletion();
    }

    // Function to validate current step
    function validateCurrentStep() {
        const currentStepElement = document.querySelector(`.form-step[data-step="${currentStep}"]`);
        
        // Remove previous errors
        const oldErrors = currentStepElement.querySelectorAll('.step-error');
        oldErrors.forEach(error => error.remove());

        let isValid = true;
        let errorMessage = '';

        // Step 1: Property Type validation
        if (currentStep === 1) {
            const propertyType = document.getElementById('propertyType').value;
            if (!propertyType) {
                errorMessage = 'Please select whether you want to sell or rent the property.';
                isValid = false;
            }
        }

        // Step 2: Property Category validation
        if (currentStep === 2) {
            const propertyCategoryId = document.getElementById('property_category_id').value;
            const childTypeId = document.getElementById('child_type_id').value;
            
            if (!propertyCategoryId) {
                errorMessage = 'Please select a property category (Residential, Commercial, Industrial, or Land).';
                isValid = false;
            } else if (!childTypeId) {
                errorMessage = 'Please select a specific property type.';
                isValid = false;
            }
        }

        // Step 3: Location validation
        if (currentStep === 3) {
            const propertyName = currentStepElement.querySelector('input[name="propertyName"]').value.trim();
            const address = currentStepElement.querySelector('input[name="address"]').value.trim();
            const latitude = document.getElementById('latitude').value;
            const longitude = document.getElementById('longitude').value;

            if (!propertyName) {
                errorMessage = 'Please enter a property name.';
                isValid = false;
            } else if (!address) {
                errorMessage = 'Please enter the property address.';
                isValid = false;
            } else if (!latitude || !longitude) {
                errorMessage = 'Please select a location on the map.';
                isValid = false;
            }
        }

        // Step 4: Bedrooms and Bathrooms validation
        if (currentStep === 4) {
            const bedrooms = document.getElementById('bedrooms').value;
            const bathrooms = document.getElementById('bathrooms').value;

            if (!bedrooms) {
                errorMessage = 'Please select the number of bedrooms.';
                isValid = false;
            } else if (!bathrooms) {
                errorMessage = 'Please select the number of bathrooms.';
                isValid = false;
            }
        }

        // Step 5: Area validation
        if (currentStep === 5) {
            const builtArea = currentStepElement.querySelector('input[name="builtArea"]').value.trim();

            if (!builtArea) {
                errorMessage = 'Please enter the built-up area.';
                isValid = false;
            }
        }

        // Step 6: Furnished and Balcony validation
        if (currentStep === 6) {
            const furnished = document.getElementById('furnished').value;
            const balcony = document.getElementById('balcony').value;

            if (!furnished) {
                errorMessage = 'Please select whether the property is furnished.';
                isValid = false;
            } else if (!balcony) {
                errorMessage = 'Please select whether the property has a balcony.';
                isValid = false;
            }
        }

        // Step 7: Community validation

        // if (currentStep === 7) {
        //     const community = document.getElementById('community').value;
        //     const status = document.getElementById('status').value;

        //     if (!community) {
        //         errorMessage = 'Please select whether the community is gated.';
        //         isValid = false;
        //     }
        //     else if (!status) {
        //         errorMessage = 'Please select the property status.';
        //         isValid = false;
        //     }
        // }
        if (currentStep === 7) {
            const community = document.getElementById('community').value.trim();
            const status = document.getElementById('status').value.trim();

            if (!community) {
                errorMessage = 'Please select whether the community is gated.';
                isValid = false;
            }
            else if (!status) {
                errorMessage = 'Please select the property status.';
                isValid = false;
            }
        }


        // Step 9: Pricing validation
        if (currentStep === 9) {
            const price = currentStepElement.querySelector('input[name="price"]').value.trim();
            const asc = currentStepElement.querySelector('input[name="asc"]').value.trim();
            const mortgaged = document.getElementById('mortgaged').value;

            if (!price) {
                errorMessage = 'Please enter the property price.';
                isValid = false;
            } else if (!asc) {
                errorMessage = 'Please enter Annual Service Charges.';
                isValid = false;
            } else if (!mortgaged) {
                errorMessage = 'Please select whether the property is mortgaged.';
                isValid = false;
            }
        }

        // Show error message if validation fails
        if (!isValid) {
            const errorElement = document.createElement('div');
            errorElement.className = 'step-error alert alert-danger';
            errorElement.style.marginTop = '15px';
            errorElement.style.padding = '10px';
            errorElement.style.borderRadius = '5px';
            errorElement.innerHTML = '<strong>Error:</strong> ' + errorMessage;
            currentStepElement.appendChild(errorElement);
            
            // Scroll to error
            errorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        return isValid;
    }

    // Function to check step completion and enable/disable next button
    function checkStepCompletion() {
        const currentStepElement = document.querySelector(`.form-step[data-step="${currentStep}"]`);
        const targetInput = currentStepElement.getAttribute('data-target');
        
        if (targetInput) {
            const input = document.getElementById(targetInput);
            nextBtn.disabled = !input || !input.value;
        } else {
            nextBtn.disabled = false;
        }
    }

    // Handle selectable buttons
    document.querySelectorAll('.selectable').forEach(button => {
        button.addEventListener('click', function() {
            const group = this.getAttribute('data-group');
            const target = this.closest('.form-step').getAttribute('data-target');
            const value = this.getAttribute('data-value');

            // Remove previous errors when user makes a selection
            const currentStepElement = this.closest('.form-step');
            const oldErrors = currentStepElement.querySelectorAll('.step-error');
            oldErrors.forEach(error => error.remove());

            // Handle grouped buttons (bedrooms, bathrooms, etc.)
            if (group) {
                const groupButtons = document.querySelectorAll(`[data-group="${group}"]`);
                groupButtons.forEach(btn => btn.classList.remove('selected'));
                this.classList.add('selected');
                
                const hiddenInput = document.getElementById(group);
                if (hiddenInput) {
                    hiddenInput.value = value;
                }
            } 
            // Handle targeted buttons (propertyType, property_category_id)
            else if (target) {
                const targetButtons = this.closest('.form-step').querySelectorAll('.selectable');
                targetButtons.forEach(btn => btn.classList.remove('selected'));
                this.classList.add('selected');
                
                const targetInput = document.getElementById(target);
                if (targetInput) {
                    targetInput.value = value;
                }
            }
            // Handle community buttons
            else if (this.getAttribute('name') === 'community') {
                const communityButtons = document.querySelectorAll('[name="community"]');
                communityButtons.forEach(btn => btn.classList.remove('selected'));
                this.classList.add('selected');
                
                const communityInput = document.getElementById('community');
                if (communityInput) {
                    communityInput.value = value;
                }
            }

            checkStepCompletion();
        });
    });

    // Handle property type child selection (Step 2)
    const parentButtons = document.querySelectorAll('#parentGroup .selectable');
    const childGroup = document.getElementById('childGroup');
    const dynamicChildButtons = document.getElementById('dynamicChildButtons');

    const childTypes = {
        residential: [
            { name: 'Apartment', value: '1' },
            { name: 'Villa', value: '2' },
            { name: 'Townhouse', value: '3' },
            { name: 'Penthouse', value: '4' }
        ],
        commercial: [
            { name: 'Office', value: '5' },
            { name: 'Retail', value: '6' },
            { name: 'Warehouse', value: '7' }
        ],
        industrial: [
            { name: 'Factory', value: '8' },
            { name: 'Workshop', value: '9' }
        ],
        land: [
            { name: 'Residential Plot', value: '10' },
            { name: 'Commercial Plot', value: '11' }
        ]
    };

    parentButtons.forEach(button => {
        button.addEventListener('click', function() {
            const type = this.getAttribute('data-type');
            const categoryValue = this.getAttribute('data-value');
            
            document.getElementById('property_category_id').value = categoryValue;
            document.getElementById('child_type_id').value = ''; // Reset child selection
            
            dynamicChildButtons.innerHTML = '';
            
            if (childTypes[type]) {
                childTypes[type].forEach(child => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'selectable';
                    btn.setAttribute('data-value', child.value);
                    btn.textContent = child.name;
                    
                    btn.addEventListener('click', function() {
                        document.querySelectorAll('#dynamicChildButtons .selectable').forEach(b => {
                            b.classList.remove('selected');
                        });
                        this.classList.add('selected');
                        document.getElementById('child_type_id').value = child.value;
                        checkStepCompletion();
                        
                        // Remove error when child is selected
                        const currentStepElement = this.closest('.form-step');
                        const oldErrors = currentStepElement.querySelectorAll('.step-error');
                        oldErrors.forEach(error => error.remove());
                    });
                    
                    dynamicChildButtons.appendChild(btn);
                });
                
                childGroup.style.display = 'block';
            }
            
            checkStepCompletion();
        });
    });

    // Monitor text input changes
    document.querySelectorAll('input[type="text"], textarea').forEach(input => {
        input.addEventListener('input', function() {
            // Remove error when user starts typing
            const currentStepElement = this.closest('.form-step');
            if (currentStepElement) {
                const oldErrors = currentStepElement.querySelectorAll('.step-error');
                oldErrors.forEach(error => error.remove());
            }
        });
    });

    // Handle price label change based on property type

    const priceLabel = document.getElementById('priceLabel');
    const step1 = document.querySelector('[data-step="1"]');
    const propertyTypeButtons = step1.querySelectorAll('.selectable');

    propertyTypeButtons.forEach(button => {
        button.addEventListener('click', function () {
            const value = this.getAttribute('data-value');
            if (value === '1') {
                priceLabel.innerText = 'Selling Price *';
            } else if (value === '2') {
                priceLabel.innerText = 'Monthly Rent *';

                document.getElementById('anualServiceCharge').style.display = 'none';
                const ascInput = document.querySelector('input[name="asc"]');
                if (ascInput) ascInput.value = '0';
            }
        });
    });

    

    // Price input - numbers only
    const priceInput = document.getElementById("myPrice");
    if (priceInput) {
        priceInput.addEventListener("input", function () {
            this.value = this.value.replace(/[^0-9]/g, "");
        });
    }

    // Select All Amenities functionality
    const selectAllBtn = document.getElementById('selectAllAmenitiesBtn');
    const checkboxes = document.querySelectorAll('.amenity-checkboxes input[type="checkbox"]');
    let allSelected = false;

    if (selectAllBtn) {
        selectAllBtn.addEventListener('click', function () {
            checkboxes.forEach(cb => cb.checked = !allSelected);
            allSelected = !allSelected;
            selectAllBtn.innerHTML = allSelected
                ? '<i class="fa fa-times-circle me-1"></i> Deselect All Amenities'
                : '<i class="fa fa-check-square me-1"></i> Select All Amenities';

            if (allSelected) {
                selectAllBtn.classList.add('active');
            } else {
                selectAllBtn.classList.remove('active');
            }
        });
    }

    // Initialize button states
    checkStepCompletion();
});
</script>