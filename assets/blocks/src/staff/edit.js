import { useSelect } from '@wordpress/data';



export default function Edit() {
  const staff = useSelect(
    ( select ) => {
      return select('core').getEntityRecords( 'postType', 'cp_staff', {
        'per_page': 4
      } )
    }
  )
  
  console.log(staff)

  let staffComponent;

  if(staff?.length) {
    staffComponent = (
      <div>
        { 
          staff.map( member => {
            return <h1>{ member.title.rendered }</h1>
          })
        }
      </div>
    )
  }
  else if(!staff) {
    staffComponent = "Loading..."
  }
  else {
    staffComponent = "No staff found"
  }

  return staffComponent
}