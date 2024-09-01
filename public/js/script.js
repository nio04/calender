document.addEventListener("click", function (e) {
  const showAllBtn = document.getElementById("show-all-btn");

  if (e.target.id === "show-all-btn") {
    async function getAllNames() {
      const res = await fetch("/fetch-all");
      const data = await res.json();

      const { success, data: names } = data.data;

      console.log(success);

      if (success) {
        hideSpinner();

        render("#greeting-response", names.map((name) => name.name).join(", "));
      } else {
        hideSpinner();
        render("#greeting-response", names);
      }
    }

    try {
      getAllNames();
    } catch (error) {
      console.error(error);
    } finally {
      showSpinner();
    }
  }
});

document.addEventListener("submit", async function (e) {
  e.preventDefault();

  const name = document.getElementById("name").value.trim();

  const validateForm = validate(name);

  if (validateForm.length > 0) {
    validateForm.forEach((err) => render("#error", err));
    return;
  }

  const formData = new FormData(document.querySelector("form"));

  try {
    showSpinner();

    const fetching = await fetchData("/form-submit", formData);

    const { success, data } = fetching.data;

    if (!success) {
      data.forEach((data) => {
        render("#error", data);
      });
      return;
    }

    render("#greeting-response", data);
  } catch (error) {
    console.error(error);
  } finally {
    hideSpinner();
  }
});

/**
 * fetch data from php
 * @param route uri to activate router
 * @param data fetch data instruction
 *
 * @returns
 */
async function fetchData(route, fetchData = []) {
  try {
    const res = await fetch(route, {
      method: "POST",
      body: fetchData,
    });
    const data = await res.json();

    return data;
  } catch (error) {
    console.error(`err: ${error}`);
  }
}

/**
 *
 * @param {string} parent querySelector to select the parent
 * @param {*} data data to rnder within parent
 */
function render(parent, data, clearParent = true) {
  document.querySelector(parent).innerHTML = "";
  if (clearParent) document.querySelector("#name").value = "";

  document
    .querySelector(parent)
    .insertAdjacentHTML("beforeend", `<p class="font-xl">${data}</p>`);
}

function validate(name) {
  const errors = [];

  if (!name) {
    errors.push("name can not be empty");
  }

  return errors.length > 0 ? errors : [];
}

function showSpinner() {
  document.querySelector("#loading-spinner").classList.remove("hidden");
}
function hideSpinner() {
  document.querySelector("#loading-spinner").classList.add("hidden");
}
