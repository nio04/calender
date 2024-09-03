import { autoLoadEvents } from "./practsies/practise1.js";
import { handleFormInput, removeErrorMsg, renderErrors, submitToServer, validateData } from "./practsies/practise2.js";

document.addEventListener("DOMContentLoaded", async function (e) {
   if (window.location.pathname === "/practise-1") {
    await autoLoadEvents("/p1-getAllEvents");
  }
});

document.addEventListener("click", async function (ev) {
})

document.addEventListener("submit", function (ev) {
  ev.preventDefault()
  const formData = handleFormInput(ev);
  
  const validate = validateData(formData);

  if (Array.isArray(validate)) {
    // there are no errors
    removeErrorMsg()

    submitToServer(formData)
    
  } else {
    // there is errors
    removeErrorMsg()
    renderErrors(validate);
    return
  }

})
