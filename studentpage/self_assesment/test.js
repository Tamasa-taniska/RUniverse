// Function to handle Alcohol Addiction questionnaire
function handleAlcoholAddiction(event) {
    event.preventDefault();
    
    let score = 0;
    for (let i = 1; i <= 10; i++) {
        const selectedOption = document.querySelector(`input[name="question${i}"]:checked`);
        if (selectedOption) {
            score += parseInt(selectedOption.value);
        }
    }

    let resultMessage = '';

    if (score <= 7) {
        resultMessage = 'Low risk. Your drinking is within the low-risk range. Continue to monitor your alcohol consumption.';
    } else if (score <= 15) {
        resultMessage = 'Medium risk. Your drinking habits may pose a risk to your health. Consider reducing your alcohol intake.';
    } else if (score <= 19) {
        resultMessage = 'High risk. Your drinking is at a high-risk level. It is advisable to seek professional help.';
    } else {
        resultMessage = 'Very high risk. Immediate professional intervention is recommended.';
    }

    document.getElementById('result').innerText = resultMessage;
}

// Function to handle Internet Addiction questionnaire
function handleInternetAddiction(event) {
    event.preventDefault();
    
    let score = 0;
    for (let i = 1; i <= 20; i++) {
        const selectedOption = document.querySelector(`input[name="question${i}"]:checked`);
        if (selectedOption) {
            score += parseInt(selectedOption.value);
        }
    }

    let resultMessage = '';

    if (score >= 20 && score <= 49) {
        resultMessage = 'Average user. You seem to have a balanced relationship with the Internet.';
    } else if (score >= 50 && score <= 79) {
        resultMessage = 'Moderate addiction. You may be experiencing occasional or frequent problems due to Internet use. Consider moderating your usage.';
    } else if (score >= 80) {
        resultMessage = 'Severe addiction. Your Internet use is causing significant problems in your life. It is advisable to seek professional help.';
    }

    document.getElementById('result').innerText = resultMessage;
}

// Function to handle Manageability questionnaire
function handleManageability(event) {
    event.preventDefault();
    
    let score = 0;
    for (let i = 1; i <= 10; i++) {
        const selectedOption = document.querySelector(`input[name="question${i}"]:checked`);
        if ( selectedOption) {
            score += parseInt(selectedOption.value);
        }
    }

    let resultMessage = '';

    if (score <= 9) {
        resultMessage = 'Low manageability issues. You seem to have good manageability skills. Keep up the good work!';
    } else if (score <= 19) {
        resultMessage = 'Moderate manageability issues. You may experience occasional manageability issues. Consider adopting time management and organizational strategies.';
    } else if (score <= 29) {
        resultMessage = 'High manageability issues. You are experiencing significant manageability issues. It is advisable to seek support and develop better coping strategies.';
    } else {
        resultMessage = 'Very high manageability issues. Immediate professional intervention is recommended.';
    }

    document.getElementById('result').innerText = resultMessage;
}
// Function to handle Coping & Resilience questionnaire
function handleCopingResilience(event) {
    event.preventDefault();
    
    let score = 0;
    for (let i = 1; i <= 10; i++) {
 const selectedOption = document.querySelector(`input[name="question${i}"]:checked`);
        if (selectedOption) {
            score += parseInt(selectedOption.value);
        }
    }

    let resultMessage = '';

    if (score <= 9) {
        resultMessage = 'Low resilience. You may be struggling with coping and resilience. Consider seeking support and developing positive coping strategies.';
    } else if (score <= 19) {
        resultMessage = 'Moderate resilience. You have some resilience but may benefit from additional support and coping strategies.';
    } else if (score <= 29) {
        resultMessage = 'High resilience. You have good resilience skills. Continue to use positive coping strategies and seek support when needed.';
    } else {
        resultMessage = 'Very high resilience. You have excellent resilience skills. Keep up the great work and continue to support others.';
    }

    document.getElementById('result').innerText = resultMessage;
}

// Function to handle Anger questionnaire
function handleAngerAssessment(event) {
    event.preventDefault();
    
    let score = 0;
    for (let i = 1; i <= 10; i++) {
        const selectedOption = document.querySelector(`input[name="question${i}"]:checked`);
        if (selectedOption) {
            score += parseInt(selectedOption.value);
        }
    }

    let resultMessage = '';

    if (score <= 9) {
        resultMessage = 'Low anger issues. You seem to have good control over your anger. Continue to monitor your emotions and seek support if needed.';
    } else if (score <= 19) {
        resultMessage = 'Moderate anger issues. You may experience occasional anger issues. Consider developing strategies to manage your anger.';
    } else if (score <= 29) {
        resultMessage = 'High anger issues. You are experiencing significant anger issues. It is advisable to seek professional help.';
    } else {
        resultMessage = 'Very high anger issues. You are facing severe anger issues. Immediate professional intervention is recommended.';
    }

    document.getElementById('result').innerText = resultMessage;
}
// Function to handle Anxiety questionnaire
function handleAnxietyAssessment(event) {
    event.preventDefault();
    
    let score = 0;
    for (let i = 1; i <= 10; i++) {
        const selectedOption = document.querySelector(`input[name="question${i}"]:checked`);
        if (selectedOption) {
            score += parseInt(selectedOption.value);
        }
    }

    let resultMessage = '';

    if (score <= 9) {
        resultMessage = 'Low anxiety. You seem to have good control over your anxiety. Continue to monitor your emotions and seek support if needed.';
    } else if (score <= 19) {
        resultMessage = 'Moderate anxiety. You may experience occasional anxiety. Consider developing strategies to manage your anxiety.';
    } else if (score <= 29) {
        resultMessage = 'High anxiety. You are experiencing significant anxiety. It is advisable to seek professional help.';
    } else {
        resultMessage = 'Very high anxiety. You are facing severe anxiety. Immediate professional intervention is recommended.';
    }

    document.getElementById('result').innerText = resultMessage;
}

// Function to handle Bullying & Harassment questionnaire
function handleBullyingAssessment(event) {
    event.preventDefault();
    
    let score = 0;
    for (let i = 1; i <= 10; i++) {
        const selectedOption = document.querySelector(`input[name="question${i}"]:checked`);
        if (selectedOption) {
            score += parseInt(selectedOption.value);
        }
    }

    let resultMessage = '';

    if (score <= 9) {
        resultMessage = 'Low bullying and harassment experiences. It appears that you have not frequently experienced bullying or harassment. Continue to stay vigilant and seek support if needed.';
    } else if (score <= 19) {
        resultMessage = 'Moderate bullying and harassment experiences. You may have experienced occasional bullying or harassment. Consider seeking support from trusted individuals or professional help.';
    } else if (score <= 29) {
        resultMessage = 'High bullying and harassment experiences. You have experienced significant bullying or harassment. It is important to seek support and take measures to protect yourself.';
    } else {
        resultMessage = 'Very high bullying and harassment experiences. You are facing severe bullying or harassment. Immediate professional intervention and support are recommended.';
    }

    document.getElementById('result').innerText = resultMessage;
}
// Function to handle General Depression questionnaire
function handleDepressionAssessment(event) {
    event.preventDefault();
    
    let score = 0;
    for (let i = 1; i <= 10; i++) {
        const selectedOption = document.querySelector(`input[name="question${i}"]:checked`);
        if (selectedOption) {
 score += parseInt(selectedOption.value);
        }
    }

    let resultMessage = '';

    if (score <= 9) {
        resultMessage = 'Low depression. You seem to have good control over your mood. Continue to monitor your emotions and seek support if needed.';
    } else if (score <= 19) {
        resultMessage = 'Moderate depression. You may experience occasional depression. Consider developing strategies to manage your mood.';
    } else if (score <= 29) {
        resultMessage = 'High depression. You are experiencing significant depression. It is advisable to seek professional help.';
    } else {
        resultMessage = 'Very high depression. You are facing severe depression. Immediate professional intervention is recommended.';
    }

    document.getElementById('result').innerText = resultMessage;
}
// Function to handle Study Stress questionnaire
function handleStudyStressAssessment(event) {
    event.preventDefault();
    
    let score = 0;
    for (let i = 1; i <= 10; i++) {
        const selectedOption = document.querySelector(`input[name ="question${i}"]:checked`);
        if (selectedOption) {
            score += parseInt(selectedOption.value);
        }
    }

    let resultMessage = '';

    if (score <= 9) {
        resultMessage = 'Low study stress. You seem to have good control over your study stress. Continue to monitor your stress levels and seek support if needed.';
    } else if (score <= 19) {
        resultMessage = 'Moderate study stress. You may experience occasional study stress. Consider developing strategies to manage your stress.';
    } else if (score <= 29) {
        resultMessage = 'High study stress. You are experiencing significant study stress. It is advisable to seek professional help.';
    } else {
        resultMessage = 'Very high study stress. You are facing severe study stress. Immediate professional intervention is recommended.';
    }

    document.getElementById('result').innerText = resultMessage;
}

// Event listeners for each form
document.addEventListener('DOMContentLoaded', function() {
    const alcoholForm = document.getElementById('assessment-form1');
    if (alcoholForm) {
        alcoholForm.addEventListener('submit', handleAlcoholAddiction);
    }

    const internetForm = document.getElementById('assessment-form2');
    if (internetForm) {
        internetForm.addEventListener('submit', handleInternetAddiction);
    }
    document.addEventListener('DOMContentLoaded', function() {
        const manageabilityForm = document.getElementById('assessment-form3');
        if (manageabilityForm) {
            manageabilityForm.addEventListener('submit', handleManageability);
        }
    });
    document.addEventListener('DOMContentLoaded', function() {
        const copingResilienceForm = document.getElementById('assessment-form4');
        if (copingResilienceForm) {
            copingResilienceForm.addEventListener('submit', handleCopingResilience);
        }
    });
    document.addEventListener('DOMContentLoaded', function() {
        const angerForm = document.getElementById('assessment-form5');
        if (angerForm) {
            angerForm.addEventListener('submit', handleAngerAssessment);
        }
    });
    document.addEventListener('DOMContentLoaded', function() {
        const anxietyForm = document.getElementById('assessment-form6');
        if (anxietyForm) {
            anxietyForm.addEventListener('submit', handleAnxietyAssessment);
        }
    });
    document.addEventListener('DOMContentLoaded', function() {
        const bullyingForm = document.getElementById('assessment-form7');
        if (bullyingForm) {
            bullyingForm.addEventListener('submit', handleBullyingAssessment);
        }
    });
    document.addEventListener('DOMContentLoaded', function() {
        const depressionForm = document.getElementById('assessment-form8');
        if (depressionForm) {
            depressionForm.addEventListener('submit', handleDepressionAssessment);
        }
    });
    document.addEventListener('DOMContentLoaded', function() {
        const studyStressForm = document.getElementById('assessment-form');
        if (studyStressForm) {
            studyStressForm.addEventListener('submit', handleStudyStressAssessment);
        }
    });
    
});