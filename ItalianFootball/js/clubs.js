
function updateClubList() {
  const formData = new FormData(document.getElementById("club_form"));

  fetch("footballshow.php", {
    // Change to your PHP script
    method: "POST",
    body: formData,
  })
    .then((response) => {
      if (response.ok) {
        return response.text(); // or response.json() based on your response type
      }
      throw new Error("Network response was not ok.");
    })
    .then((data) => {
      document.getElementById("club_response").innerHTML = data; // Display the response
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}

updateClubList() 

function openClubModal(stadiumName, stadiumCity, clubName) {
  const modal    = document.getElementById("club_modal");
  const backdrop = document.getElementById("backdrop");
  const content  = document.getElementById("club_modal_content");

  const formData = new FormData();
  formData.append("stadium_name", stadiumName);
  formData.append("stadium_city", stadiumCity);
  formData.append("club_name", clubName);

  fetch("clubmodalshow.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.text())
    .then((html) => {
      content.innerHTML = html;          // this contains the Google Maps iframe
      modal.style.display = "block";
      backdrop.style.display = "block";
    })
    .catch((error) => {
      console.error("Error loading modal:", error);
      content.innerHTML = "<p>Sorry, couldn't load the map.</p>";
      modal.style.display = "block";
      backdrop.style.display = "block";
    });
}




/* Close the modal */
let closeModal = () => {
    document.getElementById("club_modal").style.display = "none";
    document.getElementById("backdrop").style.display = "none";
}
