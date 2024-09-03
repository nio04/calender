
/**
 * fetch data from php
 * @param route uri to activate router
 * @param data fetch data instruction
 *
 * @returns
 */
export async function fetchData(route, fetchData = []) {
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
 * @param {string} data html code data to rnder within parent
 */
export function render(parent, data) {
  document.querySelector(parent).innerHTML = "";

  document
    .querySelector(parent)
    .insertAdjacentHTML("beforeend", data);
}

export function showSpinner() {
  document.querySelector("#loading-spinner").classList.remove("hidden");
}

export function hideSpinner() {
  document.querySelector("#loading-spinner").classList.add("hidden");
}
