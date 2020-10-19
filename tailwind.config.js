module.exports = {
  theme: {
  	customForms: theme => ({
      dark: {
	        'input, textarea, multiselect, select, checkbox, radio': {
	        	borderColor: theme('colors.gray.600'),
	      	},
	    },
    }),
    colors: {
        accent: 'var(--text-accent-color)',
        'accent-light': 'var(--text-accent-light-color)',
        muted: 'var(--text-muted-color)',
        'muted-light': 'var(--text-muted-light-color)',
        error:'var(--text-error-color)',
    },
    backgroundColor: theme => ({
    	page: 'var(--page-background-color)',
    	card: 'var(--card-background-color)',
    	button: 'var(--button-background-color)',
    	header: 'var(--header-background-color)',
    }),
    extend: {
      colors: {
        default: 'var(--text-default-color)',
      },
	  	boxShadow: {
	  		default: '0 0 5px 0 rgba(0, 0, 0, 0.08)',
	  		button: '0 2px 7px 0 #b0eaff',
	  	},
    },
  },
  variants: {},
  plugins: [
      require('@tailwindcss/custom-forms'),
  ],
}
