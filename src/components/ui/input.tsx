import * as React from "react"

import { cn } from "@/lib/utils"

function Input({ className, type, ...props }: React.ComponentProps<"input">) {
  return (
    <input
      type={type}
      data-slot="input"
      className={cn(
        "apbl:h-9 apbl:w-full apbl:min-w-0 apbl:rounded-md apbl:border apbl:border-input apbl:bg-transparent apbl:px-3 apbl:py-1 apbl:text-base apbl:shadow-xs apbl:transition-[color,box-shadow] apbl:outline-none apbl:selection:bg-primary apbl:selection:text-primary-foreground apbl:file:inline-flex apbl:file:h-7 apbl:file:border-0 apbl:file:bg-transparent apbl:file:text-sm apbl:file:font-medium apbl:file:text-foreground apbl:placeholder:text-muted-foreground apbl:disabled:pointer-events-none apbl:disabled:cursor-not-allowed apbl:disabled:opacity-50 apbl:md:text-sm apbl:dark:bg-input/30",
        "apbl:focus-visible:border-ring apbl:focus-visible:ring-[3px] apbl:focus-visible:ring-ring/50",
        "apbl:aria-invalid:border-destructive apbl:aria-invalid:ring-destructive/20 apbl:dark:aria-invalid:ring-destructive/40",
        className
      )}
      {...props}
    />
  )
}

export { Input }
