export function handleFormInput(ev) {
  const firstName = ev.target[0].value;
  const lastName = ev.target[1].value;
  const age = ev.target[2].value;
  const gender = (ev.target[3].checked && ev.target[3].value) || (ev.target[4].checked && ev.target[4].value) || (ev.target[5].checked && ev.target[5].value);
  const hobbies = [(ev.target[6].checked && ev.target[6].value), (ev.target[7].checked && ev.target[7].value)];
  const about = ev.target[8].value;

  return {
    firstName: firstName,
    lastName: lastName,
    age: age,
    gender: gender,
    hobbies: hobbies.filter(hobby=>hobby !== false ),
    about: about
  }
}

export function validateData(data) {
  const errors = {}
  for (const key in data) {
    if (key === 'firstName' && data[key].length < 1) {
      errors.firstName = "first name can not be empty"
    }
    if (key === 'lastName' && data[key].length < 1) {
      errors.lastName = "last name can not be empty"
    }
    if (key === 'age' && data[key].length < 1) {
      errors.age = "age can not be empty"
    }
    if (key === 'gender' && data[key].length < 1) {
      errors.gender = "gender can not be empty"
    }
  }
  return Object.keys(errors).length > 0 ? errors : [];
}

export async function submitToServer(formData) {
  try {
    const res = await fetch("/p2-submit-form", {
      'method': 'POST',
      headers: {
        'Content-type': 'application/json',
      },
      body: JSON.stringify(formData)
      
    });
    const data = await res.json();
    if (data.success) {
      alert(`data saved on database success. id: ${data.id}`)
    } else {
      const errors = {};

      for (const key in data.data) {
        errors[key] = data.data[key]
      }

      renderErrors(errors)
    }
  } catch (error) {
    console.error(error);
    
  }
}

export function renderErrors(errors) {
  for (const key in errors) {
    const parent = document.querySelector(`[data-container='${key}']`);
    parent.insertAdjacentHTML("beforeend", `<p class="error-msg text-red-500 col-span-full text-sm">${errors[key]}</p>`)
  }
}

export function removeErrorMsg() {
  [...document.querySelectorAll(".error-msg")].forEach(msg=>msg.remove())
}
