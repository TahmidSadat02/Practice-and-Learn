import phonenumbers
from phonenumbers import geocoder, carrier

def track_number(phone_number):
    try:
        parsed_number = phonenumbers.parse(phone_number)
        
        if not phonenumbers.is_valid_number(parsed_number):
            return "Invalid phone number format. Please include country code (e.g., +1 for USA)"
            
        location = geocoder.description_for_number(parsed_number, "en")
        
        service_provider = carrier.name_for_number(parsed_number, "en")
        
        return f"Location: {location}\nService Provider: {service_provider}"
    except Exception as e:
        return f"Error: {str(e)}"

if __name__ == "__main__":
    print("Phone Number Tracker")
    print("===================")
    print("Please enter a phone number with country code (e.g., +8801648504964)")
    phone_number = input("Enter phone number: ")
    
    result = track_number(phone_number)
    print("\nResults:")
    print(result)
