// set Rules for realtime database

// {
//   "rules": {
//     ".read": false,
//     ".write": true
//   }
// }

// set Rules for firestore database

// rules_version = '2';
// service cloud.firestore {
//   match / databases / { database } / documents {
//     match / { document=**} {
//       allow read: if false;
//       allow write: if true;
//     }
//   }
// }

//   copy your firebase config informations
const firebaseConfig = {
  apiKey: "AIzaSyBiqOo9eGTPFxJ3FxXKlBqD0SUwo7-hx3M",
  authDomain: "h-anatomy.firebaseapp.com",
  databaseURL: "https://h-anatomy-default-rtdb.firebaseio.com",
  projectId: "h-anatomy",
  storageBucket: "h-anatomy.appspot.com",
  messagingSenderId: "685959849400",
  appId: "1:685959849400:web:eaaf6be6d42877aa7bdb4d",
  measurementId: "G-H4PHVS3XJM"
};

// initialize firebase
firebase.initializeApp(firebaseConfig);

// reference your database
var contactFormDB = firebase.database().ref("contactForm");

document.getElementById("contactForm").addEventListener("submit", submitForm);

function submitForm(e) {
  e.preventDefault();

  const getElementVal = (id) => {
    return document.getElementById(id).value;
  };

  var name = getElementVal("name");
  var email = getElementVal("email");
  var subject = getElementVal("subject");
  var message = getElementVal("message");


  // Check if any of the input fields are empty
  if (name === "" || email === "" || subject === "" || message === "") {
    // Display an alert if any of the input fields are empty
    window.alert("Please fill out all the required fields!");
  } else {
    // Check if the email address is in the correct format
    let emailRegex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (!emailRegex.test(email)) {
      // Display an alert if the email address is not in the correct format
      window.alert("Please enter a valid email address!");
    }
    // Display an alert if the email address is not ending with @gmail.com
    else if (email.substring(email.length - 10) !== "@gmail.com") {
      window.alert("Please enter a valid email address ending with @gmail.com!");
    }

    else {
      saveMessages(name, email, subject, message);

      //   enable alert
      document.querySelector(".alert").style.display = "block";
      // window.alert("Message sent successfully...🥰");

      //   remove the alert
      setTimeout(() => {
        document.querySelector(".alert").style.display = "none";
      }, 3000);

      //   reset the form
      setTimeout(() => {
        document.getElementById("contactForm").reset();
      }, 3000);
    }
  }
}

const saveMessages = (name, email, subject, message) => {
  var newContactForm = contactFormDB.push();

  newContactForm.set({
    name: name,
    email: email,
    subject: subject,
    message: message,
  });
};



