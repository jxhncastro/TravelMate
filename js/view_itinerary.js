$(document).ready(function () {
    // Show trip duration modal
    $("#create-trip-btn").click(function (e) {
        e.preventDefault();
        $("#trip-modal").fadeIn();
    });

    // Close trip duration modal
    $(".close-btn").click(function () {
        $("#trip-modal").fadeOut();
    });

    // Handle duration selection
    $(".duration-btn").click(function () {
        let days = $(this).data("days");
        $("#selected_days").val(days);
        $("#trip-modal").fadeOut(); // Close trip modal

        let itineraryHtml = "";

        switch (days) {
            case 3:
                itineraryHtml = `<h4>3-DAYS ITINERARY</h4>
                    <h5><strong>Day 1</strong></h5>
                    <ul>
                        <li>Check-in at a DOT-accredited accommodation establishment in Laoag City</li>
                        <li>Solsona-Apayao Road</li>
                        <li>Lunch at Solsona</li>
                        <li>St. Augustine Parish Church</li>
                        <li>Malacañang of the North</li>
                        <li>Sinking Bell Tower</li>
                        <li>Ilocos Norte Provincial Capitol</li>
                        <li>Dinner at Laoag City</li>
                    </ul>
                    <h5><strong>Day 2</strong></h5>
                    <ul>
                        <li>La Virgen Milagrosa</li>
                        <li>Badoc Island</li>
                        <li>Lunch at Badoc</li>
                        <li>Batac Empanadaan</li>
                        <li>Marcos Museum</li>
                        <li>Immaculate Conception Parish - Batac Church</li>
                        <li>Batac Mini Park</li>
                        <li>Paoay Sand Dunes</li>
                        <li>Dinner at food establishments along the Paoay Lake</li>
                    </ul>
                    <h5><strong>Day 3</strong></h5>
                    <ul>
                        <li>Kapurpurawan Rock Formation</li>
                        <li>Pagudpud Arch</li>
                        <li>Check-in at Pagudpud and lunch</li>
                        <li>Saud Beach</li>
                        <li>Dinner at Pagudpud</li>
                    </ul>`;
                break;
            case 4:
                itineraryHtml = `<h4>4-DAYS ITINERARY</h4>
                    <h5><strong>Day 1</strong></h5>
                    <ul>
                        <li>Check-in at a DOT-accredited accommodation establishment in Laoag City</li>
                        <li>Solsona-Apayao Road</li>
                        <li>Lunch at Solsona</li>
                        <li>St. Augustine Parish Church</li>
                        <li>Malacañang of the North</li>
                        <li>Sinking Bell Tower</li>
                        <li>Ilocos Norte Provincial Capitol</li>
                        <li>Dinner at Laoag City</li>
                    </ul>
                    <h5><strong>Day 2</strong></h5>
                    <ul>
                        <li>La Virgen Milagrosa</li>
                        <li>Badoc Island</li>
                        <li>Lunch at Badoc</li>
                        <li>Batac Empanadaan</li>
                        <li>Marcos Museum</li>
                        <li>Immaculate Conception Parish - Batac Church</li>
                        <li>Batac Mini Park</li>
                        <li>Paoay Sand Dunes</li>
                        <li>Dinner at food establishments along the Paoay Lake</li>
                    </ul>
                    <h5><strong>Day 3</strong></h5>
                    <ul>
                        <li>Kapurpurawan Rock Formation</li>
                        <li>Pagudpud Arch</li>
                        <li>Check-in at Pagudpud and lunch</li>
                        <li>Saud Beach</li>
                        <li>Dinner at Pagudpud</li>
                    </ul>
                    <h5><strong>Day 4</strong></h5>
                    <ul>
                        <li>Bantay Abot Cave</li>
                        <li>Lunch at Barangay Pancian, Pagudpud</li>
                        <li>Patapat Viaduct</li>
                        <li>Blue Lagoon</li>
                        <li>Dinner at Pagudpud</li>
                    </ul>`;
                break;
            case 5:
                itineraryHtml = `<h4>5-DAYS ITINERARY</h4>
                    <h5><strong>Day 1</strong></h5>
                    <ul>
                        <li>Check-in at a DOT-accredited accommodation establishment in Laoag City</li>
                        <li>Solsona-Apayao Road</li>
                        <li>Lunch at Solsona</li>
                        <li>St. Augustine Parish Church</li>
                        <li>Malacañang of the North</li>
                        <li>Sinking Bell Tower</li>
                        <li>Ilocos Norte Provincial Capitol</li>
                        <li>Dinner at Laoag City</li>
                    </ul>
                    <h5><strong>Day 2</strong></h5>
                    <ul>
                        <li>La Virgen Milagrosa</li>
                        <li>Badoc Island</li>
                        <li>Lunch at Badoc</li>
                        <li>Batac Empanadaan</li>
                        <li>Marcos Museum</li>
                        <li>Immaculate Conception Parish - Batac Church</li>
                        <li>Batac Mini Park</li>
                        <li>Paoay Sand Dunes</li>
                        <li>Dinner at food establishments along the Paoay Lake</li>
                    </ul>
                    <h5><strong>Day 3</strong></h5>
                    <ul>
                        <li>Kapurpurawan Rock Formation</li>
                        <li>Pagudpud Arch</li>
                        <li>Check-in at Pagudpud and lunch</li>
                        <li>Saud Beach</li>
                        <li>Dinner at Pagudpud</li>
                    </ul>
                    <h5><strong>Day 4</strong></h5>
                    <ul>
                        <li>Bantay Abot Cave</li>
                        <li>Lunch at Barangay Pancian, Pagudpud</li>
                        <li>Patapat Viaduct</li>
                        <li>Blue Lagoon</li>
                        <li>Dinner at Pagudpud</li>
                    </ul>
                    <h5><strong>Day 5</strong></h5>
                    <ul>
                        <li>Free-time or swimming at the Blue Lagoon</li>
                        <li>Lunch at Barangay Balaoi, Pagudpud</li>
                        <li>Cape Bojeador Lighthouse</li>
                        <li>Dinner at Laoag City or San Nicolas</li>
                    </ul>`;
                break;
            default:
                itineraryHtml = "<p>Please select a valid duration.</p>";
        }

        // Insert itinerary content
        $("#suggestions").html(itineraryHtml);
        $("#suggested-itineraries").fadeIn(); // Show the suggested itinerary modal
    });
});


