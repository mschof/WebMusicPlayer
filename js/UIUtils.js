class UIUtils {

  static showTopMessage(text, lifetime)
  {
    // Create message
    var container_element = document.createElement("div");
    container_element.classList.add("message-top");
    var text_element = document.createElement("span");
    var close_element = document.createElement("a");
    container_element.appendChild(text_element);
    container_element.appendChild(close_element);
    text_element.innerHTML = text;
    close_element.title = "close";
    close_element.href = "javascript:void(0)";
    close_element.innerHTML = "[x]";

    // Create function to close
    var close_function = function() { if(document.body.contains(container_element)) document.body.removeChild(container_element); };
    close_element.onclick = close_function;

    // Add to body
    document.body.appendChild(container_element);

    // Remove from body after <lifetime> seconds
    setTimeout(close_function, lifetime * 1000);

  }

  static getIndexInParent(element)
  {
    var el_index = 0;
    var el_copy = element;
    while((el_copy = el_copy.previousSibling) != null) {
      if(el_copy.nodeType == Node.ELEMENT_NODE) {
        el_index++;
      }
    }
    return el_index;
  }

}
